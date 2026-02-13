<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $status;
    public $siteName;
    public $siteLogo;
    public $siteEmail;
    public $sitePhone;
    public $siteAddress;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, string $status)
    {
        $this->order = $order;
        $this->status = $status;
        $this->siteName = Setting::get('site_name', 'The Trusted Store');
        $this->siteLogo = Setting::get('site_logo', '');
        $this->siteEmail = Setting::get('site_email', '');
        $this->sitePhone = Setting::get('site_phone', '');
        $this->siteAddress = Setting::get('site_address', '');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $statusText = ucfirst($this->status);
        
        return new Envelope(
            subject: 'Order ' . $statusText . ' - ' . $this->order->order_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order-status-updated',
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
