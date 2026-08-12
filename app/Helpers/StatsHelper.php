<?php

namespace App\Helpers;

use App\Models\Dress;
use App\Models\Discount;

class StatsHelper
{
    /**
     * Safely get product count
     */
    public static function productCount(): int
    {
        try {
            return Dress::count();
        } catch (\Throwable $e) {
            return 0;
        }
    }

    /**
     * Safely get active discount count
     */
    public static function activeDiscountCount(): int
    {
        try {
            return Discount::where('active', true)->count();
        } catch (\Throwable $e) {
            return 0;
        }
    }

    /**
     * Get all stats safely
     */
    public static function allStats(): array
    {
        return [
            'product_count' => self::productCount(),
            'active_discount_count' => self::activeDiscountCount(),
        ];
    }
}