<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dress;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartApiController extends Controller
{
    public function __construct(private CartService $cart)
    {
    }

    public function index()
    {
        $items = $this->cart->itemsWithDress();

        return response()->json([
            'items' => $items,
            'cart_count' => $this->cart->count(),
            'subtotal' => $this->formatSubtotal($items),
        ]);
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:dresses,id',
            'quantity' => 'nullable|integer|min:1|max:'.CartService::MAX_QUANTITY_PER_ITEM,
            'size' => 'nullable|string|max:20',
            'color' => 'nullable|string|max:30',
        ]);

        $dress = Dress::findOrFail($validated['product_id']);
        $size = $validated['size'] ?? null;
        $color = $validated['color'] ?? null;

        // A dress only requires a size/color pick if the admin actually
        // configured options for it — dresses with none configured show a
        // "Contact us" fallback on the product page instead of a picker.
        $availableSizes = array_map('strval', $dress->sizes ?? []);
        if (count($availableSizes) > 0 && (! $size || ! in_array($size, $availableSizes, true))) {
            return response()->json([
                'success' => false,
                'message' => 'Please select a size.',
            ], 422);
        }

        $availableColors = $dress->colors ?? [];
        if (count($availableColors) > 0 && (! $color || ! in_array($color, $availableColors, true))) {
            return response()->json([
                'success' => false,
                'message' => 'Please select a color.',
            ], 422);
        }

        $requestedQuantity = $validated['quantity'] ?? 1;
        $before = $this->cart->quantityFor($validated['product_id'], $size, $color);

        $this->cart->add($validated['product_id'], $requestedQuantity, $size, $color);
        $after = $this->cart->quantityFor($validated['product_id'], $size, $color);
        $capped = $after < $before + $requestedQuantity;

        return response()->json([
            'success' => true,
            'capped' => $capped,
            'cart_count' => $this->cart->count(),
            'message' => $capped
                ? 'You already have the maximum of '.CartService::MAX_QUANTITY_PER_ITEM.' of this item in your cart.'
                : 'Added to your cart.',
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:dresses,id',
            'quantity' => 'required|integer|min:0|max:'.CartService::MAX_QUANTITY_PER_ITEM,
            'size' => 'nullable|string|max:20',
            'color' => 'nullable|string|max:30',
        ]);

        $this->cart->update($request->product_id, $request->quantity, $request->size, $request->color);
        $items = $this->cart->itemsWithDress();

        return response()->json([
            'success' => true,
            'cart_count' => $this->cart->count(),
            'subtotal' => $this->formatSubtotal($items),
            'items' => $items,
        ]);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:dresses,id',
            'size' => 'nullable|string|max:20',
            'color' => 'nullable|string|max:30',
        ]);

        $this->cart->remove($request->product_id, $request->size, $request->color);
        $items = $this->cart->itemsWithDress();

        return response()->json([
            'success' => true,
            'cart_count' => $this->cart->count(),
            'subtotal' => $this->formatSubtotal($items),
            'items' => $items,
        ]);
    }

    private function formatSubtotal(array $items): string
    {
        $subtotal = array_reduce($items, fn ($carry, $item) => $carry + $item['subtotal'], 0);

        return 'R'.number_format($subtotal, 0);
    }
}
