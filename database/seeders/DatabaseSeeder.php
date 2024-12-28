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
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'admin@admin.com',
        ]);

        Role::create([
            'name' => 'Super Admin',
            'guard_name' => 'web'
        ]);

        $user->assignRole('Super Admin');

        Category::create([
            'name' => 'OTC'
        ]);

        Category::create([
            'name' => 'Prescription'
        ]);

        // Category::factory(2)->create();
        Branch::factory(20)->create();
        // Generic::factory(50)->create();
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
