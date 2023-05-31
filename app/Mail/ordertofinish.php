<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ordertofinish extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $product_id;
    public $email;
    public $address;
    public $phone_number;
    public $description;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $product_id, $email, $address, $phone_number, $description)
    {
        $this->name = $name;
        $this->product_id = $product_id;
        $this->email = $email;
        $this->address = $address;
        $this->phone_number = $phone_number;
        $this->description = $description;
    }



    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'ordertofinish',
        );
    }

    public function build()
    {
        return $this->markdown('Mails.ordertofinish');
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
