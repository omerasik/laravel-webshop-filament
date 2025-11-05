<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(3, true),
            'description' => $this->faker->paragraphs(3, true),
            'price' => $this->faker->randomFloat(2, 5, 250),
            'stock' => $this->faker->numberBetween(0, 100),
            'image' => null,
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
        ];
    }
}
