<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Setting;

class AdminPasswordResetNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $resetTime;
    public $ipAddress;
    public $userAgent;
    public $siteName;
    public $siteLogo;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $ipAddress = null)
    {
        $this->user = $user;
        $this->resetTime = now()->format('d M, Y h:i A');
        $this->ipAddress = $ipAddress ?? request()->ip();
        $this->userAgent = request()->userAgent();
        $this->siteName = Setting::get('site_name', config('app.name'));
        $this->siteLogo = Setting::get('site_logo', '');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔐 Password Reset Alert - User: ' . $this->user->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-password-reset-notification',
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
