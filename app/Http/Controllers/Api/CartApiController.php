<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartApiController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        $items = $this->buildItems($cart);

        return response()->json([
            'items' => $items,
            'cart_count' => array_sum($cart),
            'subtotal' => $this->calculateSubtotal($items),
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:dresses,id',
            'quantity' => 'nullable|integer|min:1|max:10',
        ]);

        $cart = Session::get('cart', []);
        $id = $request->product_id;
        $quantity = $request->quantity ?? 1;

        $cart[$id] = ($cart[$id] ?? 0) + $quantity;
        Session::put('cart', $cart);

        return response()->json([
            'success' => true,
            'cart_count' => array_sum($cart),
            'message' => 'Added to your cart.',
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:dresses,id',
            'quantity' => 'required|integer|min:0|max:10',
        ]);

        $cart = Session::get('cart', []);
        $id = $request->product_id;
        $quantity = $request->quantity;

        if ($quantity <= 0) {
            unset($cart[$id]);
        } else {
            $cart[$id] = $quantity;
        }

        Session::put('cart', $cart);
        $items = $this->buildItems($cart);

        return response()->json([
            'success' => true,
            'cart_count' => array_sum($cart),
            'subtotal' => $this->calculateSubtotal($items),
            'items' => $items,
        ]);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:dresses,id',
        ]);

        $cart = Session::get('cart', []);
        unset($cart[$request->product_id]);
        Session::put('cart', $cart);
        $items = $this->buildItems($cart);

        return response()->json([
            'success' => true,
            'cart_count' => array_sum($cart),
            'subtotal' => $this->calculateSubtotal($items),
            'items' => $items,
        ]);
    }

    private function buildItems(array $cart): array
    {
        $items = [];
        $dressIds = array_keys($cart);

        if (count($dressIds) === 0) {
            return [];
        }

        $dresses = Dress::whereIn('id', $dressIds)->active()->get()->keyBy('id');

        foreach ($cart as $id => $quantity) {
            $dress = $dresses->get($id);
            if ($dress) {
                $items[] = [
                    'dress' => $dress,
                    'quantity' => $quantity,
                    'subtotal' => $dress->price * $quantity,
                ];
            }
        }

        return $items;
    }

    private function calculateSubtotal(array $items): string
    {
        $subtotal = array_reduce($items, fn ($carry, $item) => $carry + $item['subtotal'], 0);
        return 'R' . number_format($subtotal, 0);
    }
}
