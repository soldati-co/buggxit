<?php

namespace Database\Factories;

use App\Models\Dress;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DressFactory extends Factory
{
    protected $model = Dress::class;

    public function definition()
    {
        $name = $this->faker->words(3, true);

        return [
            'slug' => Str::slug($name).'-'.$this->faker->unique()->numberBetween(1000, 9999),
            'name' => $name,
            'sku' => 'DRESS-'.strtoupper(Str::random(8)),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 500, 5000),
            'stock_quantity' => $this->faker->numberBetween(0, 20),
            'turnaround_time' => '2-3 weeks',
            'expected_delivery' => 'To the nearest Courier',
            'status' => 'active',
            'is_featured' => false,
            'sizes' => [34, 36, 38],
            'colors' => ['red', 'blue'],
            'is_taxable' => true,
            'requires_shipping' => true,
        ];
    }
}
