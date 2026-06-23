<?php

namespace App\Services;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Events\Dispatcher;
use Symfony\Component\Mailer\Mailer as SymfonyMailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;

/**
 * Kirim email lewat POOL akun Gmail SMTP: pilih akun acak, dan bila gagal coba
 * akun lain (failover). Tujuannya menyebar beban & menghindari limit/blok satu
 * akun. From email = alamat akun Gmail yang dipakai (Gmail menimpa From).
 *
 * Konfigurasi: config('services.gmail_pool') ← MAIL_GMAIL_POOL (.env, JSON).
 */
class GmailPoolMailer
{
    /**
     * Kirim Mailable via salah satu akun Gmail (acak, dengan failover).
     *
     * @return bool true bila terkirim oleh salah satu akun.
     * @throws \RuntimeException bila pool kosong atau SEMUA akun gagal.
     */
    public function send(string $to, Mailable $mailable): bool
    {
        $cfg = config('services.gmail_pool');
        $accounts = $cfg['accounts'] ?? [];

        if (empty($accounts)) {
            throw new \RuntimeException('Gmail pool kosong — set MAIL_GMAIL_POOL di .env.');
        }

        // Acak urutan akun: pilihan pertama = random, sisanya jadi urutan failover.
        shuffle($accounts);

        $lastError = null;

        foreach ($accounts as $i => $account) {
            try {
                $this->sendVia($account, $cfg, $to, $mailable);

                if ($i > 0) {
                    Log::info('Gmail pool: terkirim setelah failover', [
                        'account' => $account['user'], 'attempt' => $i + 1,
                    ]);
                }

                return true;
            } catch (\Throwable $e) {
                $lastError = $e;
                Log::warning('Gmail pool: akun gagal kirim, coba akun lain', [
                    'account' => $account['user'] ?? '?',
                    'error' => $e->getMessage(),
                ]);
                // lanjut ke akun berikutnya
            }
        }

        throw new \RuntimeException(
            'Semua akun Gmail pool gagal mengirim email'
            . ($lastError ? ': ' . $lastError->getMessage() : '.')
        );
    }

    /**
     * Kirim 1 Mailable lewat 1 akun Gmail spesifik (transport SMTP eksplisit).
     */
    protected function sendVia(array $account, array $cfg, string $to, Mailable $mailable): void
    {
        $host = $cfg['host'] ?? 'smtp.gmail.com';
        $port = (int) ($cfg['port'] ?? 587);

        // EsmtpTransport: port 465 = TLS implisit (SMTPS); selain itu STARTTLS.
        $transport = new EsmtpTransport($host, $port, $port === 465);
        $transport->setUsername($account['user']);
        $transport->setPassword($account['pass']);

        // Mailer Laravel dibungkus di atas Symfony mailer untuk akun ini.
        $mailer = new Mailer(
            'gmail-pool',
            View::getFacadeRoot(),
            new SymfonyMailer($transport),
            app(Dispatcher::class)
        );

        // From = alamat Gmail akun ini (Gmail akan menimpa From ke akun pengirim).
        $from = $account['from'] ?? $account['user'];
        $name = $account['name'] ?? config('mail.from.name');
        $mailer->alwaysFrom($from, $name);

        $mailer->to($to)->send($mailable);
    }
}
