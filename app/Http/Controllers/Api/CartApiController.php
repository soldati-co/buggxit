<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        $request->validate([
            'product_id' => 'required|exists:dresses,id',
            'quantity' => 'nullable|integer|min:1|max:10',
        ]);

        $this->cart->add($request->product_id, $request->quantity ?? 1);

        return response()->json([
            'success' => true,
            'cart_count' => $this->cart->count(),
            'message' => 'Added to your cart.',
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:dresses,id',
            'quantity' => 'required|integer|min:0|max:10',
        ]);

        $this->cart->update($request->product_id, $request->quantity);
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
        ]);

        $this->cart->remove($request->product_id);
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
