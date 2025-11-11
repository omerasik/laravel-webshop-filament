<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Brand>
 */
class BrandFactory extends Factory
{
    protected $model = Brand::class;

    private const BRANDS = [
        'Studio Water & Zeep',
        'Apotheek De Linde',
        'Schoonheidshuis Bloom',
        'Huidatelier Noord',
        'Glow & Co.',
        'Wellness aan de Schelde',
        'Pure Balance Antwerp',
        'Salon Van der Veen',
        'Lief Huidlab',
        'Botanica Gent',
        'Polder Beauty',
        'Zorgboetiek Lumen',
    ];

    public function definition(): array
    {
        $name = $this->faker->unique()->randomElement(self::BRANDS);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
