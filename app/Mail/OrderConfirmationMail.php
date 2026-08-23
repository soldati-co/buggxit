<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->replyTo(config('mail.store_notification_address'))
                    ->subject('Your Buggxit Couture order '.$this->order->order_number.' is confirmed')
                    ->view('emails.order-confirmation');
    }
}
