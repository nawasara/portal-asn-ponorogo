<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WaNotificationService
{
    protected $baseUrl;
    protected $realm;
    protected $clientId;
    protected $clientSecret;

    public function __construct()
    {
        $this->baseUrl      = config('services.whatsapp.api_url');
        $this->token        = config('services.whatsapp.api_key');
    }

    public function sendWa($to, $message)
    {
        $client = new Client();

        $response = $client->post($this->baseUrl.'/wago/sendMessage', [
            'headers' => [
                'Authorization' => 'Bearer '.$this->token,
                'Content-Type'  => 'application/json',
            ],
            'json' => [
                'phone'   => $to,
                'message' => $message,
            ]
        ]);

        return $response->getBody()->getContents();

    }

    /**
     * Cek apakah nomor terdaftar di WhatsApp.
     *
     * Return false HANYA bila gateway menjawab dengan tegas nomor tidak ada
     * di WhatsApp. Bila gateway error (sesi terputus / 401 / 5xx / timeout),
     * lempar RuntimeException supaya pemanggil bisa membedakan "nomor invalid"
     * dari "layanan WhatsApp bermasalah" — JANGAN telan jadi false.
     *
     * @throws \RuntimeException kalau layanan WhatsApp tidak bisa dihubungi.
     */
    public function checkNumber($to): bool
    {
        $client = new Client(['http_errors' => false]);

        try {
            $response = $client->get($this->baseUrl.'/wago/userCheck?phone='.$to, [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->token,
                    'Content-Type'  => 'application/json',
                ],
                'timeout' => 15,
            ]);
        } catch (\Throwable $th) {
            // Transport gagal (DNS, koneksi, timeout) — bukan jawaban "nomor tidak ada".
            info('WA userCheck gagal terhubung: '.$th->getMessage());
            throw new \RuntimeException('Layanan WhatsApp sedang tidak dapat dihubungi.');
        }

        $status  = $response->getStatusCode();
        $content = $response->getBody()->getContents();
        $json    = json_decode($content, true);

        // Gateway WAGO membungkus error di body (mis. sesi terputus → code 401
        // "you are not connect to services server"). HTTP-nya kadang 200/401.
        $apiCode = $json['code'] ?? $status;
        if ($status >= 400 || (is_int($apiCode) && $apiCode >= 400)) {
            info('WA userCheck error', ['http' => $status, 'body' => $content]);
            throw new \RuntimeException(
                'Layanan WhatsApp sedang bermasalah'.
                (isset($json['message']) ? ': '.$json['message'] : '').'.'
            );
        }

        // Sukses — barulah jawaban is_on_whatsapp dianggap valid.
        return (bool) ($json['results']['is_on_whatsapp'] ?? false);
    }

}