<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Option 1: Insert manually
        $products = [
            ['name' => 'iPhone 15', 'category' => 'Electronics', 'price' => 79999, 'quantity' => 10],
            ['name' => 'Samsung TV 55"', 'category' => 'Electronics', 'price' => 55999, 'quantity' => 5],
            ['name' => 'Men T-Shirt', 'category' => 'Clothing', 'price' => 999, 'quantity' => 50],
            ['name' => 'Office Chair', 'category' => 'Furniture', 'price' => 4999, 'quantity' => 20],
            ['name' => 'Sofa Set', 'category' => 'Furniture', 'price' => 25999, 'quantity' => 3],
            ['name' => 'Washing Machine', 'category' => 'Electronics', 'price' => 18999, 'quantity' => 8],
            ['name' => 'Jeans Denim', 'category' => 'Clothing', 'price' => 1499, 'quantity' => 40],
            ['name' => 'Dining Table', 'category' => 'Furniture', 'price' => 11999, 'quantity' => 5],
            ['name' => 'Bluetooth Speaker', 'category' => 'Electronics', 'price' => 2999, 'quantity' => 15],
            ['name' => 'Jacket Winter', 'category' => 'Clothing', 'price' => 1999, 'quantity' => 25],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        // Option 2: Add random data for large list
        Product::factory()->count(40)->create();
    }
}