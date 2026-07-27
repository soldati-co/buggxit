<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Models\Address;
use App\Models\Dress;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CheckoutApiController extends Controller
{
    public function store(CheckoutRequest $request)
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return response()->json(['message' => 'Your cart is empty.'], 422);
        }

        DB::beginTransaction();

        try {
            $shippingAddress = $this->resolveShippingAddress($request);
            $billingAddress = $shippingAddress;

            $items = $this->buildItems($cart);
            if (empty($items)) {
                throw new \Exception('Cart contains invalid items.');
            }

            $subtotal = array_reduce($items, fn ($carry, $item) => $carry + $item['subtotal'], 0);
            $shippingCost = 0;
            $total = $subtotal + $shippingCost;

            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'user_id' => auth()->id(),
                'shipping_address_id' => $shippingAddress->id,
                'billing_address_id' => $billingAddress->id,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'notes' => $request->input('notes'),
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'dress_id' => $item['dress']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['dress']->price,
                ]);
            }

            Session::forget('cart');
            DB::commit();

            return response()->json([
                'success' => true,
                'order' => $order,
                'redirect_url' => route('checkout.success', $order->id),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Checkout failed. Please try again.', 'error' => $e->getMessage()], 422);
        }
    }

    private function resolveShippingAddress(CheckoutRequest $request): Address
    {
        if ($request->filled('shipping_address_id')) {
            $address = Address::findOrFail($request->shipping_address_id);
            if ($address->user_id !== auth()->id()) {
                abort(403);
            }
            return $address;
        }

        return Address::create([
            'user_id' => auth()->id(),
            'address_line1' => $request->address_line1,
            'address_line2' => $request->address_line2,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'phone' => $request->phone,
            'is_default' => false,
        ]);
    }

    private function buildItems(array $cart): array
    {
        $dressIds = array_keys($cart);
        $dresses = Dress::whereIn('id', $dressIds)->active()->get()->keyBy('id');

        $items = [];
        foreach ($cart as $id => $quantity) {
            $dress = $dresses->get($id);
            if (! $dress) {
                continue;
            }
            $items[] = [
                'dress' => $dress,
                'quantity' => $quantity,
                'subtotal' => $dress->price * $quantity,
            ];
        }

        return $items;
    }
}
