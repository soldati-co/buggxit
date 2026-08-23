<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Address;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cart,
        private OrderService $orders,
    ) {
    }

    public function index()
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $items = $this->cart->itemsWithDress();
        $subtotal = array_reduce($items, fn ($carry, $item) => $carry + $item['subtotal'], 0);

        // For logged in users, fetch their saved addresses
        $addresses = auth()->check() ? auth()->user()->addresses : collect();

        return view('checkout.index', compact('items', 'subtotal', 'addresses'));
    }

    public function store(CheckoutRequest $request)
    {
        if ($this->cart->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        try {
            $shippingAddress = $this->resolveShippingAddress($request);

            // Billing address: a separate billing-address form isn't implemented yet,
            // so we always bill to the shipping address regardless of same_as_shipping.
            $order = $this->orders->createOrderFromCart(
                shippingAddress: $shippingAddress,
                billingAddress: null,
                paymentMethod: $request->payment_method,
                userId: auth()->id(),
                notes: $request->input('notes'),
                email: $request->input('email') ?: auth()->user()?->email,
            );

            // Guests need a way back to their own order confirmation without
            // exposing every order to anyone who guesses the id — a signed
            // URL proves this visit is the legitimate post-checkout one.
            $targetRoute = $order->payment_method === 'payfast' ? 'payfast.redirect' : 'checkout.success';

            return redirect(URL::temporarySignedRoute(
                $targetRoute,
                now()->addHours(48),
                ['order' => $order->id],
            ));
        } catch (\Throwable $e) {
            return back()->with('error', 'Checkout failed. Please try again.')->withInput();
        }
    }

    public function success(Request $request, Order $order)
    {
        $isOwner = auth()->check() && $order->user_id == auth()->id();

        if (! $request->hasValidSignature() && ! $isOwner) {
            abort(403);
        }

        return view('checkout.success', compact('order'));
    }

    private function resolveShippingAddress(Request $request): Address
    {
        if ($request->shipping_address_id) {
            $address = Address::findOrFail($request->shipping_address_id);
            if ($address->user_id != auth()->id()) {
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
}