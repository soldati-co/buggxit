<?php

namespace App\Services;

use App\Models\Dress;
use Illuminate\Support\Facades\Session;

/**
 * Wraps the session-backed cart (a flat [dress_id => quantity] array).
 * There is no Cart/CartItem database model — cart state lives entirely in
 * the session, matching the app's existing design.
 */
class CartService
{
    private const SESSION_KEY = 'cart';

    /**
     * Per-item cap on how many of one dress a customer can order. Enforced
     * here (not just in request validation) because add() accumulates onto
     * whatever quantity is already in the cart — capping only the quantity
     * on a single request would still let repeated "Add to Cart" clicks
     * push the total past the limit.
     */
    public const MAX_QUANTITY_PER_ITEM = 5;

    public function all(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    public function count(): int
    {
        return array_sum($this->all());
    }

    public function add(string $dressId, int $quantity = 1): array
    {
        $cart = $this->all();
        $requested = ($cart[$dressId] ?? 0) + $quantity;
        $cart[$dressId] = min($requested, self::MAX_QUANTITY_PER_ITEM);
        Session::put(self::SESSION_KEY, $cart);

        return $cart;
    }

    public function update(string $dressId, int $quantity): array
    {
        $cart = $this->all();

        if ($quantity <= 0) {
            unset($cart[$dressId]);
        } else {
            $cart[$dressId] = min($quantity, self::MAX_QUANTITY_PER_ITEM);
        }

        Session::put(self::SESSION_KEY, $cart);

        return $cart;
    }

    public function remove(string $dressId): array
    {
        $cart = $this->all();
        unset($cart[$dressId]);
        Session::put(self::SESSION_KEY, $cart);

        return $cart;
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    public function isEmpty(): bool
    {
        return empty($this->all());
    }

    /**
     * Hydrate cart entries with their active Dress models, silently dropping
     * entries whose dress no longer exists or is no longer active.
     *
     * @return array<int, array{dress: Dress, quantity: int, subtotal: float}>
     */
    public function itemsWithDress(): array
    {
        $cart = $this->all();

        if (empty($cart)) {
            return [];
        }

        $dresses = Dress::whereIn('id', array_keys($cart))->active()->get()->keyBy('id');

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

    public function subtotal(): float
    {
        return array_reduce(
            $this->itemsWithDress(),
            fn ($carry, $item) => $carry + $item['subtotal'],
            0
        );
    }
}
