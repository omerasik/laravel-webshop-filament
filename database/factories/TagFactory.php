<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Tag>
 */
class TagFactory extends Factory
{
    protected $model = Tag::class;

    private const TAGS = [
        'gevoelige huid',
        'droge huid',
        'glow',
        'anti-aging',
        'vitamine c',
        'vegan',
        'geurvrij',
        'natuurlijk',
        'hydraterend',
        'kalmerend',
        'zonbescherming',
        'herstellend',
        'scrub',
        'massage',
        'limited edition',
    ];

    public function definition(): array
    {
        $name = $this->faker->unique()->randomElement(self::TAGS);

        return [
            'name' => Str::title($name),
            'slug' => Str::slug($name),
        ];
    }
}
