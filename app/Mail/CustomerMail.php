<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Product;

class CustomerMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $address;
    public $phone_number;
    public $description;

    public $productprice;
    public $deliverytime = '10-15 business days';


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $email, $address, $phone_number, $description,  $productprice)
    {
        $this->name = $name;
        $this->email = $email;
        $this->address = $address;
        $this->phone_number = $phone_number;
        $this->description = $description;
        $this->productprice = $productprice;
        //$this->deliveryTime = $deliveryTime;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'CustomerMail',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function build()
    {
        return $this->markdown('Mails.CustomerMail');
    }


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
