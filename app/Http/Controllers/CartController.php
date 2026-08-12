<?php

namespace App\Http\Controllers;

use App\Services\CartService;

class CartController extends Controller
{
    public function __construct(private CartService $cart)
    {
    }

    /**
     * Display the cart page.
     */
    public function index()
    {
        $items = $this->cart->itemsWithDress();
        $subtotal = array_reduce($items, fn ($carry, $item) => $carry + $item['subtotal'], 0);

        return view('cart.index', [
            'items'    => $items,
            'subtotal' => $subtotal,
        ]);
    }
}
