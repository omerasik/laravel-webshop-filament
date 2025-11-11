<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    private const CATEGORIES = [
        'Gezichtsreiniging',
        'Dag- en nachtcreme',
        'Serums',
        'Maskers',
        'Lichaamsverzorging',
        'Hand- en voetverzorging',
        'Haar- en hoofdhuid',
        'Bad & Douche',
        'Make-up basics',
        'Cadeausets',
        'Wellness accessoires',
    ];

    public function definition(): array
    {
        $name = $this->faker->unique()->randomElement(self::CATEGORIES);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
