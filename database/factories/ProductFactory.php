<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'generic_id' => rand(1, 20),
            'category_id' => rand(1, 2),
            'parent_id' => rand(1, 20),
            'sku' => fake()->ean8(),
            'name' => fake()->colorName(),
            'description' => fake()->sentence(),
            'cost' => rand(10, 100),
            'price' => rand(50, 1000),
            'expired_at' => (Carbon::now())->addMonths(rand(2, 12))
        ];
    }
}
