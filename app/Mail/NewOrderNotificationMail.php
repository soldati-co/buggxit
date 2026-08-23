<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOrderNotificationMail extends Mailable
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
        return $this->subject('New paid order '.$this->order->order_number.' - R'.number_format((float) $this->order->total, 2))
                    ->view('emails.new-order-notification')
                    ->attachData($this->receiptPdf, 'Receipt-'.$this->order->order_number.'.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}
