<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Booking;

class TicketConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Booking $booking;
    public $bookingFoods;
    public string $ticketCode;

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking, $bookingFoods = null)
    {
        $this->booking = $booking;
        $this->bookingFoods = $bookingFoods ?? collect([]);
        $this->ticketCode = 'HCTV-' . sprintf('%06d', $booking->id);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $movieTitle = $this->booking->showtime->movie->title ?? 'Vé xem phim';
        return new Envelope(
            subject: "[HCTV Cinema] Vé điện tử: {$movieTitle} - Mã vé {$this->ticketCode}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket-confirmation',
            with: [
                'booking' => $this->booking,
                'bookingFoods' => $this->bookingFoods,
                'ticketCode' => $this->ticketCode,
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
