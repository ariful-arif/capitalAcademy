<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GiftedCourseMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    // App\Mail\GiftedCourseMail.php
    public $user, $password, $courses;

    public function __construct($user, $password, $courses)
    {
        $this->user = $user;
        $this->password = $password;
        $this->courses = $courses;
    }

    public function build()
    {
        return $this->subject('You’ve been gifted a course!')
            ->view('email.gifted_course');
    }

}
