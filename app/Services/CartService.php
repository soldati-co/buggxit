<?php

namespace App\Services;

use App\Models\Dress;
use Illuminate\Support\Facades\Session;

/**
 * Wraps the session-backed cart (a list of line-item arrays, each keyed by
 * dress_id + size + color so the same dress can appear more than once with
 * different selections). There is no Cart/CartItem database model — cart
 * state lives entirely in the session, matching the app's existing design.
 */
class CartService
{
    private const SESSION_KEY = 'cart';

    /**
     * Per-item cap on how many of one dress/size/color combination a
     * customer can order. Enforced here (not just in request validation)
     * because add() accumulates onto whatever quantity is already in the
     * cart — capping only the quantity on a single request would still let
     * repeated "Add to Cart" clicks push the total past the limit.
     */
    public const MAX_QUANTITY_PER_ITEM = 5;

    public function all(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    public function count(): int
    {
        return array_sum(array_column($this->all(), 'quantity'));
    }

    /**
     * Current quantity for a specific dress/size/color line item, used to
     * detect whether an add() request got capped.
     */
    public function quantityFor(string $dressId, ?string $size = null, ?string $color = null): int
    {
        $cart = $this->all();
        $index = $this->findIndex($cart, $dressId, $size, $color);

        return $index !== null ? $cart[$index]['quantity'] : 0;
    }

    public function add(string $dressId, int $quantity = 1, ?string $size = null, ?string $color = null): array
    {
        $cart = $this->all();
        $index = $this->findIndex($cart, $dressId, $size, $color);

        if ($index !== null) {
            $cart[$index]['quantity'] = min($cart[$index]['quantity'] + $quantity, self::MAX_QUANTITY_PER_ITEM);
        } else {
            $cart[] = [
                'dress_id' => $dressId,
                'size' => $size,
                'color' => $color,
                'quantity' => min($quantity, self::MAX_QUANTITY_PER_ITEM),
            ];
        }

        Session::put(self::SESSION_KEY, $cart);

        return $cart;
    }

    public function update(string $dressId, int $quantity, ?string $size = null, ?string $color = null): array
    {
        $cart = $this->all();
        $index = $this->findIndex($cart, $dressId, $size, $color);

        if ($index !== null) {
            if ($quantity <= 0) {
                array_splice($cart, $index, 1);
            } else {
                $cart[$index]['quantity'] = min($quantity, self::MAX_QUANTITY_PER_ITEM);
            }
        }

        Session::put(self::SESSION_KEY, $cart);

        return $cart;
    }

    public function remove(string $dressId, ?string $size = null, ?string $color = null): array
    {
        $cart = $this->all();
        $index = $this->findIndex($cart, $dressId, $size, $color);

        if ($index !== null) {
            array_splice($cart, $index, 1);
        }

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
     * @return array<int, array{dress: Dress, size: ?string, color: ?string, quantity: int, subtotal: float}>
     */
    public function itemsWithDress(): array
    {
        $cart = $this->all();

        if (empty($cart)) {
            return [];
        }

        $dressIds = array_unique(array_column($cart, 'dress_id'));
        $dresses = Dress::whereIn('id', $dressIds)->active()->get()->keyBy('id');

        $items = [];
        foreach ($cart as $entry) {
            $dress = $dresses->get($entry['dress_id']);
            if (! $dress) {
                continue;
            }

            $items[] = [
                'dress' => $dress,
                'size' => $entry['size'] ?? null,
                'color' => $entry['color'] ?? null,
                'quantity' => $entry['quantity'],
                'subtotal' => $dress->price * $entry['quantity'],
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

    private function findIndex(array $cart, string $dressId, ?string $size, ?string $color): ?int
    {
        foreach ($cart as $index => $item) {
            if ((string) $item['dress_id'] === $dressId
                && ($item['size'] ?? null) === $size
                && ($item['color'] ?? null) === $color) {
                return $index;
            }
        }

        return null;
    }
}
