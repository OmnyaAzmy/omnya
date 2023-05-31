<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class contact extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $phone;
    public $email;
    public $message;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */


    public function __construct($name, $phone, $email, $message, $subject)
    {
       $this->name = $name;
       $this->phone = $phone;
       $this->email = $email;
       $this->message = $message;
       $this->subject = $subject;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'contactus',
        );
    }

    public function build()
    {
        return $this->markdown('Mails.contactus');
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */

    // public function content()
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }


    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
