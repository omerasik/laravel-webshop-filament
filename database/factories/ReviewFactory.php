<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'name' => $this->faker->firstName . ' ' . $this->faker->lastName,
            'email' => $this->faker->safeEmail,
            'rating' => $this->faker->numberBetween(3, 5),
            'comment' => $this->faker->paragraph(2),
            'is_approved' => true,
        ];
    }
}
