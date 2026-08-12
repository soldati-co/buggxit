<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PayfastService;
use Illuminate\Http\Request;

class PayfastController extends Controller
{
    public function redirect(Request $request, Order $order, PayfastService $payfast)
    {
        $isOwner = auth()->check() && $order->user_id == auth()->id();

        if (! $request->hasValidSignature() && ! $isOwner) {
            abort(403);
        }

        if ($order->payment_method !== 'payfast') {
            abort(404);
        }

        $formHtml = $payfast->paymentFormHtml($order);

        return view('payfast.redirect', compact('order', 'formHtml'));
    }
}
