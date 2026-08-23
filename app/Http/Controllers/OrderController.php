<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\ReceiptService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $orders = auth()->user()->orders()->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id != auth()->id()) {
            abort(403);
        }

        return view('orders.show', compact('order'));
    }

    public function receipt(Order $order, ReceiptService $receipts)
    {
        if ($order->user_id != auth()->id()) {
            abort(403);
        }

        $pdf = $receipts->render($order, 'customer');

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$receipts->filename($order).'"',
        ]);
    }
}