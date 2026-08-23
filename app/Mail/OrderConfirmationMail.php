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

    public string $receiptPdf;

    public function __construct(Order $order, string $receiptPdf)
    {
        $this->order = $order;
        $this->receiptPdf = $receiptPdf;
    }

    public function build()
    {
        return $this->replyTo(config('mail.store_notification_address'))
                    ->subject('Your Buggxit Couture order '.$this->order->order_number.' is confirmed')
                    ->view('emails.order-confirmation')
                    ->attachData($this->receiptPdf, 'Receipt-'.$this->order->order_number.'.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}
