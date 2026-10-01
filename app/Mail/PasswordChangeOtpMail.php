<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordChangeOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otp;
    public string $name;
    public int $expiresInMinutes;

    /**
     * Create a new message instance.
     */
    public function __construct(string $otp, string $name, int $expiresInMinutes = 10)
    {
        $this->otp = $otp;
        $this->name = $name;
        $this->expiresInMinutes = $expiresInMinutes;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[HCTV Cinema] Mã xác thực OTP: {$this->otp} - Thay đổi mật khẩu tài khoản",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.password-change-otp',
            with: [
                'otp' => $this->otp,
                'name' => $this->name,
                'expiresInMinutes' => $this->expiresInMinutes,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
