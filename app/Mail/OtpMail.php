<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  string  $otp          Kode OTP 6 digit.
     * @param  int     $ttlMinutes   Masa berlaku OTP dalam menit.
     * @param  string  $ref          Referensi unik (untuk pelacakan).
     */
    public function __construct(
        public string $otp,
        public int $ttlMinutes = 5,
        public string $ref = '',
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode OTP Reset MFA — SSO ASN Ponorogo',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.otp',
            with: [
                'otp' => $this->otp,
                'ttlMinutes' => $this->ttlMinutes,
                'ref' => $this->ref,
            ],
        );
    }
}
