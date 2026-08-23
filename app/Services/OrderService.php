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
        ?string $email = null,
        ?string $name = null,
    ): Order {
        $items = $this->cart->itemsWithDress();

        if (empty($items)) {
            throw new RuntimeException('Cart is empty or contains no available items.');
        }

        $billingAddress ??= $shippingAddress;

        return DB::transaction(function () use ($items, $shippingAddress, $billingAddress, $paymentMethod, $userId, $notes, $email, $name) {
            $subtotal = array_reduce($items, fn ($carry, $item) => $carry + $item['subtotal'], 0);
            $shippingCost = 0;
            $total = $subtotal + $shippingCost;

            $order = Order::create([
                'order_number' => 'ORD-'.strtoupper(Str::random(8)),
                'user_id' => $userId,
                'name' => $name,
                'email' => $email,
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
                    'attributes' => array_filter([
                        'size' => $item['size'] ?? null,
                        'color' => $item['color'] ?? null,
                    ]) ?: null,
                ]);
            }

            $this->cart->clear();

            return $order;
        });
    }

    /**
     * Create an Order + OrderItems from an explicit item list rather than the
     * session cart -- for orders admin staff record on a customer's behalf
     * (WhatsApp, phone, in-person) so they show up in the system exactly like
     * a web order: visible in admin, receipts generated, emailed if paid.
     *
     * @param  array<int, array{dress_id: string, quantity: int, price: float, size?: ?string, color?: ?string}>  $items
     */
    public function createManualOrder(
        array $items,
        Address $shippingAddress,
        string $paymentMethod,
        string $paymentStatus,
        string $status,
        ?string $name = null,
        ?string $email = null,
        ?string $notes = null,
    ): Order {
        if (empty($items)) {
            throw new RuntimeException('An order needs at least one item.');
        }

        return DB::transaction(function () use ($items, $shippingAddress, $paymentMethod, $paymentStatus, $status, $name, $email, $notes) {
            $subtotal = array_reduce($items, fn ($carry, $item) => $carry + ($item['price'] * $item['quantity']), 0);

            $order = Order::create([
                'order_number' => 'ORD-'.strtoupper(Str::random(8)),
                'user_id' => null,
                'name' => $name,
                'email' => $email,
                'shipping_address_id' => $shippingAddress->id,
                'billing_address_id' => $shippingAddress->id,
                'subtotal' => $subtotal,
                'shipping_cost' => 0,
                'total' => $subtotal,
                'status' => $status,
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
                'notes' => $notes,
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'dress_id' => $item['dress_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'attributes' => array_filter([
                        'size' => $item['size'] ?? null,
                        'color' => $item['color'] ?? null,
                    ]) ?: null,
                ]);
            }

            return $order;
        });
    }
}
