<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class OrderService
{
    public function __construct(private CartService $cart)
    {
    }

    /**
     * Create an Order + OrderItems from the current cart, inside a transaction,
     * and clear the cart on success. The caller is responsible for resolving
     * and authorizing the shipping/billing addresses before calling this.
     */
    public function createOrderFromCart(
        Address $shippingAddress,
        ?Address $billingAddress,
        string $paymentMethod,
        ?string $userId,
        ?string $notes = null,
    ): Order {
        $items = $this->cart->itemsWithDress();

        if (empty($items)) {
            throw new RuntimeException('Cart is empty or contains no available items.');
        }

        $billingAddress ??= $shippingAddress;

        return DB::transaction(function () use ($items, $shippingAddress, $billingAddress, $paymentMethod, $userId, $notes) {
            $subtotal = array_reduce($items, fn ($carry, $item) => $carry + $item['subtotal'], 0);
            $shippingCost = 0;
            $total = $subtotal + $shippingCost;

            $order = Order::create([
                'order_number' => 'ORD-'.strtoupper(Str::random(8)),
                'user_id' => $userId,
                'shipping_address_id' => $shippingAddress->id,
                'billing_address_id' => $billingAddress->id,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'status' => 'pending',
                'payment_method' => $paymentMethod,
                'payment_status' => 'pending',
                'notes' => $notes,
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'dress_id' => $item['dress']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['dress']->price,
                ]);
            }

            $this->cart->clear();

            return $order;
        });
    }
}
