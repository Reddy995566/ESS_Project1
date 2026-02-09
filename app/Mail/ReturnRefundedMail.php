<?php

namespace App\Mail;

use App\Models\ProductReturn;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReturnRefundedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $return;
    public $siteName;
    public $siteLogo;
    public $siteEmail;
    public $sitePhone;
    public $siteAddress;

    /**
     * Create a new message instance.
     */
    public function __construct(ProductReturn $return)
    {
        $this->return = $return;
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
        return new Envelope(
            subject: 'Refund Processed - ' . $this->return->return_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.return-refunded',
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