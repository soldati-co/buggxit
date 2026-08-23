<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderConfirmationMail;
use App\Models\Address;
use App\Models\Dress;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\PaxiPointService;
use App\Services\ReceiptService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        $dresses = Dress::where('status', 'active')->orderBy('name')->get(['id', 'name', 'price', 'sizes', 'colors']);

        return view('admin.orders.create', compact('dresses'));
    }

    public function store(Request $request, OrderService $orders, ReceiptService $receipts, PaxiPointService $paxi)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => ['nullable', 'string', 'max:255', Rule::requiredIf(fn () => $request->input('courier_method') === 'pep')],
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'payment_method' => 'required|in:eft,cash_on_delivery,whatsapp,other',
            'payment_status' => 'required|in:pending,paid',
            'status' => 'required|in:pending,processing,completed,cancelled',
            'notes' => 'nullable|string|max:2000',
            'courier_method' => 'nullable|in:courier_guy,pep',
            'pep_point_code' => 'required_if:courier_method,pep|nullable|string|max:20',
            'items' => 'required|array|min:1',
            'items.*.dress_id' => 'required|exists:dresses,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.size' => 'nullable|string|max:50',
            'items.*.color' => 'nullable|string|max:50',
        ]);

        $pepPoint = null;

        if (($validated['courier_method'] ?? null) === 'pep') {
            $pepPoint = $paxi->findByCode((string) $validated['pep_point_code']);

            if (! $pepPoint) {
                return back()->with('error', 'Please select a valid PEP point.')->withInput();
            }
        }

        $address = Address::create([
            'user_id' => null,
            'address_line1' => $validated['address_line1'],
            'address_line2' => $validated['address_line2'] ?? null,
            'city' => $validated['city'],
            'state' => $validated['state'] ?? null,
            'postal_code' => $validated['postal_code'],
            'country' => $validated['country'],
            'phone' => $validated['phone'],
            'is_default' => false,
        ]);

        $order = $orders->createManualOrder(
            items: $validated['items'],
            shippingAddress: $address,
            paymentMethod: $validated['payment_method'],
            paymentStatus: $validated['payment_status'],
            status: $validated['status'],
            name: $validated['name'],
            email: $validated['email'] ?? null,
            notes: $validated['notes'] ?? null,
            courierMethod: $validated['courier_method'] ?? null,
            pepPoint: $pepPoint,
        );

        if ($order->payment_status === 'paid' && $order->email) {
            try {
                Mail::to($order->email)->send(new OrderConfirmationMail($order, $receipts->render($order, 'customer')));
            } catch (\Throwable $e) {
                Log::error('Manual order confirmation email failed to send', [
                    'order' => $order->order_number,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        return redirect()->route('admin.orders.show', $order)->with('success', 'Order recorded successfully.');
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