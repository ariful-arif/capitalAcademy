<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CourseBundleMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $subject;
    public $description;
    public $bundle;
    public $invoice;
    public $user_id;

    /**
     * Create a new message instance.
     */
    public function __construct($mail_data)
    {
        $this->subject = $mail_data['subject'];
        $this->description = $mail_data['description'];
        $this->invoice = $mail_data['invoice'];
        $this->bundle = $mail_data['bundle'];
        $this->user_id = $mail_data['user_id'];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
  public function content(): Content
    {
        return new Content(
            markdown: 'mail.course_bundle.payment',
            with: [
                'subject'     => $this->subject,
                'description' => $this->description,
                'invoice'     => $this->invoice,
                'bundle'      => $this->bundle,
                'user_id'      => $this->user_id,
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
