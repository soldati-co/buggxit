<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;

class OrderApiController extends Controller
{
    public function index()
    {
        return OrderResource::collection(auth()->user()->orders()->latest()->paginate(10));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return new OrderResource($order->load('items.dress'));
    }
}
