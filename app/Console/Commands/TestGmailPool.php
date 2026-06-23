<?php

namespace App\Console\Commands;

use App\Mail\OtpMail;
use App\Services\GmailPoolMailer;
use Illuminate\Console\Command;
use Symfony\Component\Mailer\Mailer as SymfonyMailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;

/**
 * Uji akun Gmail pool: kirim email tes lewat SETIAP akun satu per satu, supaya
 * ketahuan akun mana yang kredensialnya salah / kena blok.
 *
 *   php artisan mail:test-gmail-pool tujuan@example.com
 *   php artisan mail:test-gmail-pool tujuan@example.com --pool   (lewat pool: random+failover)
 */
class TestGmailPool extends Command
{
    protected $signature = 'mail:test-gmail-pool {to : Alamat email tujuan} {--pool : Kirim lewat pool (random+failover), bukan tiap akun}';

    protected $description = 'Tes kirim email lewat akun Gmail pool (per akun, atau lewat pool)';

    public function handle(): int
    {
        $to = $this->argument('to');
        $cfg = config('services.gmail_pool');
        $accounts = $cfg['accounts'] ?? [];

        if (empty($accounts)) {
            $this->error('MAIL_GMAIL_POOL kosong. Set di .env dulu.');
            return self::FAILURE;
        }

        $this->info('Akun di pool: ' . count($accounts));

        $mail = new OtpMail('123456', 60, 'pool-test');

        if ($this->option('pool')) {
            try {
                app(GmailPoolMailer::class)->send($to, $mail);
                $this->info("✓ Terkirim lewat pool (random+failover) ke {$to}");
                return self::SUCCESS;
            } catch (\Throwable $e) {
                $this->error('✗ Pool gagal: ' . $e->getMessage());
                return self::FAILURE;
            }
        }

        // Uji tiap akun satu per satu.
        $host = $cfg['host'] ?? 'smtp.gmail.com';
        $port = (int) ($cfg['port'] ?? 587);
        $ok = 0;

        foreach ($accounts as $account) {
            $user = $account['user'];
            $this->line("→ Tes {$user} ...");
            try {
                $transport = new EsmtpTransport($host, $port, $port === 465);
                $transport->setUsername($user);
                $transport->setPassword($account['pass']);

                $mailer = new \Illuminate\Mail\Mailer(
                    'gmail-test',
                    \Illuminate\Support\Facades\View::getFacadeRoot(),
                    new SymfonyMailer($transport),
                    app(\Illuminate\Events\Dispatcher::class)
                );
                $mailer->alwaysFrom($account['from'] ?? $user, $account['name'] ?? config('mail.from.name'));
                $mailer->to($to)->send($mail);

                $this->info("  ✓ OK — {$user}");
                $ok++;
            } catch (\Throwable $e) {
                $this->error("  ✗ GAGAL — {$user}: " . $e->getMessage());
            }
        }

        $this->newLine();
        $this->info("Selesai: {$ok}/" . count($accounts) . " akun berhasil kirim.");

        return $ok > 0 ? self::SUCCESS : self::FAILURE;
    }
}
