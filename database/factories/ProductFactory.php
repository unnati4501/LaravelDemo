<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $categories = ['Electronics', 'Clothing', 'Furniture'];
        return [
            'name' => fake()->word(),
            'category' => fake()->randomElement($categories),
            'price' => fake()->numberBetween(500, 80000),
            'quantity' => fake()->numberBetween(1, 100),
        ];
    }
}
