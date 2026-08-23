<?php

namespace App\Services;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptService
{
    /**
     * Render the order receipt to a PDF binary string. $audience only
     * changes the badge/footer wording ('customer' vs 'store') -- the
     * underlying order data shown is identical either way.
     */
    public function render(Order $order, string $audience = 'customer'): string
    {
        $order->loadMissing('items.dress', 'shippingAddress', 'user');

        return Pdf::loadView('pdf.receipt', [
            'order' => $order,
            'audience' => $audience,
        ])->output();
    }

    public function filename(Order $order): string
    {
        return 'Receipt-'.$order->order_number.'.pdf';
    }
}
