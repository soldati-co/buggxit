<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        $subtotal = $this->faker->randomFloat(2, 100, 5000);

        return [
            'order_number' => 'ORD-'.strtoupper(Str::random(8)),
            'user_id' => null,
            'shipping_address_id' => null,
            'billing_address_id' => null,
            'subtotal' => $subtotal,
            'shipping_cost' => 0,
            'total' => $subtotal,
            'status' => 'pending',
            'payment_method' => 'eft',
            'payment_status' => 'pending',
        ];
    }
}
