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
        $this->baseUrl      = env('WHATSAPP_API_URL');
        $this->token        = env('WHATSAPP_API_KEY');
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

}