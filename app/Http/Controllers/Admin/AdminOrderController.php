<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\ReceiptService;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.dress', 'user', 'shippingAddress', 'billingAddress');
        return view('admin.orders.show', compact('order'));
    }

    public function receipt(Order $order, ReceiptService $receipts)
    {
        $pdf = $receipts->render($order, 'store');

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$receipts->filename($order).'"',
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
            'payment_status' => 'sometimes|in:pending,paid,failed'
        ]);

        $order->update($request->only(['status', 'payment_status']));

        return back()->with('success', 'Order updated successfully.');
    }
}