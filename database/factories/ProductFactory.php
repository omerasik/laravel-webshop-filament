<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    private const ADJECTIVES = [
        'verzachtende',
        'herstellende',
        'verfrissende',
        'intense',
        'balancerende',
        'kalmerende',
        'revitaliserende',
        'glansgevende',
        'zuiverende',
        'beschermende',
    ];

    private const PRODUCT_TYPES = [
        'dagcrème',
        'nachtcrème',
        'gezichtsolie',
        'reinigingsmelk',
        'micellaire lotion',
        'serum',
        'kleimasker',
        'bodyscrub',
        'handbalsem',
        'toner',
        'ooggel',
        'bodylotion',
        'douche-olie',
        'haarserum',
    ];

    private const INGREDIENTS = [
        'hyaluronzuur',
        'vitamine C',
        'duindoornolie',
        'lavendelextract',
        'kamille',
        'niacinamide',
        'bamboewater',
        'aloë vera',
        'arganolie',
        'groene thee',
        'sheaboter',
    ];

    private const BENEFITS = [
        'een droge huid',
        'de gevoelige huid',
        'een doffe teint',
        'roodheden',
        'fijne lijntjes',
        'een vermoeide uitstraling',
        'oneffenheden',
        'een trekkerig gevoel',
    ];

    private const FRAGRANCES = [
        'bloesem',
        'verse citrus',
        'cederhout',
        'sandalo',
        'katoenbloem',
        'zachte muskus',
    ];

    public function definition(): array
    {
        $name = sprintf(
            '%s %s',
            Arr::random(self::ADJECTIVES),
            Arr::random(self::PRODUCT_TYPES),
        );

        $ingredient = Arr::random(self::INGREDIENTS);
        $benefit = Arr::random(self::BENEFITS);
        $fragrance = Arr::random(self::FRAGRANCES);

        return [
            'name' => ucfirst($name),
            'description' => sprintf(
                'Deze %s is verrijkt met %s en ondersteunt %s. De lichte textuur trekt snel in en laat een subtiele geur van %s achter. Gebruik het product dagelijks voor het beste resultaat.',
                $name,
                $ingredient,
                $benefit,
                $fragrance,
            ),
            'price' => $this->faker->randomFloat(2, 5, 250),
            'stock' => $this->faker->numberBetween(0, 100),
            'image' => null,
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
        ];
    }
}
