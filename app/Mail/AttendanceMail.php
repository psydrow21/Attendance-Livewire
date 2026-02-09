<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AttendanceMail extends Mailable
{
    use Queueable, SerializesModels;

        public array $adminmail;

    /**
     * Create a new message instance.
     */
    public function __construct(array $adminmail)
    {
        //
        $this->adminmail = $adminmail;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Attendance Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.attendance',
            with : [
                'adminmail' => $this->adminmail,
            ]
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

    // public function build()
    // {
    //     $subject = 'Attendance Not Sync';
    //     $address = env('MAIL_FROM_ADDRESS');
    //     $name = env('MAIL_FROM_NAME');

    //     return $this->from('mail@example.com', 'mailtrap')
    //             ->subject($subject)
    //             ->markdown('page.dashboard');
    // }
}
