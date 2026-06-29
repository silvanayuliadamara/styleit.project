<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $type;

    /**
     * Create a new message instance.
     *
     * @param Booking $booking
     * @param string $type ('created', 'dp_confirmed', 'lunas')
     */
    public function __construct(Booking $booking, string $type = 'created')
    {
        $this->booking = $booking;
        $this->type = $type;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = match ($this->type) {
            'dp_confirmed' => 'Pembayaran DP Diterima - Invoice #' . $this->booking->booking_code . ' — LYB',
            'lunas' => 'Pembayaran Lunas - Invoice #' . $this->booking->booking_code . ' — LYB',
            default => 'Invoice Tagihan Booking #' . $this->booking->booking_code . ' — LYB',
        };

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-invoice',
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
