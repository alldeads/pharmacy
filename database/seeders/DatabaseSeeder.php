<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Generic;
use App\Models\Product;
use App\Models\Stock;
use App\Models\User;
use App\Models\Supplier;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'admin@admin.com',
        ]);

        Category::factory(20)->create();
        Branch::factory(20)->create();
        Generic::factory(50)->create();
        Product::factory(100)->create();
        Supplier::factory(100)->create();

        $products = Product::all();

        foreach ($products as $product) {
            Stock::factory()->create([
                'product_id' => $product->id,
                'branch_id' => rand(1, 20)
            ]);
        }
    }
}
