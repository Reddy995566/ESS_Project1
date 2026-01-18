<?php

namespace App\Mail;

use App\Models\BulkOrder;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminBulkOrderNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $bulkOrder;
    public $siteName;
    public $siteLogo;

    /**
     * Create a new message instance.
     */
    public function __construct(BulkOrder $bulkOrder)
    {
        $this->bulkOrder = $bulkOrder;
        $this->siteName = Setting::get('site_name', 'Fashion Store');
        $this->siteLogo = Setting::get('site_logo', '');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📦 New Bulk Order Inquiry - ' . $this->bulkOrder->business_name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-bulk-order-notification',
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
