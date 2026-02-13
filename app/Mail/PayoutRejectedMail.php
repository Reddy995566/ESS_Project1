<?php

namespace App\Mail;

use App\Models\SellerPayout;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PayoutRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $payout;
    public $rejectionReason;

    /**
     * Create a new message instance.
     */
    public function __construct(SellerPayout $payout, $rejectionReason = null)
    {
        $this->payout = $payout;
        $this->rejectionReason = $rejectionReason;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payout Rejected - ' . $this->payout->payout_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.payout-rejected',
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
