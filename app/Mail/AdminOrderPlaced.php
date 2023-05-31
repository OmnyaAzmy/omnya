<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminOrderPlaced extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $cart;

    /**
     * Create a new message instance.
     *
     * @param Order $order
     */
    public function __construct(Order $order, $cart)
    {
        $this->order = $order;
        $this->cart = $cart;

    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function build()
    {
        return $this->subject('Admin Order Placed')
                    ->view('Mails.admin_order_placed')
                    ->with([
                        'order' => $this->order,
                        'cart' => $this->cart,

                    ]);
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


