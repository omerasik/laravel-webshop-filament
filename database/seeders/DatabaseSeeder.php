<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin gebruiker',
            'email' => 'admin@webshop.test',
            'password' => Hash::make('password'),
        ]);

        $brands = Brand::factory()->count(6)->create();
        $categories = Category::factory()->count(6)->create();
        $tags = Tag::factory()->count(12)->create();

        Product::factory()
            ->count(40)
            ->state(function () use ($brands, $categories) {
                return [
                    'brand_id' => $brands->random()->id,
                    'category_id' => $categories->random()->id,
                ];
            })
            ->create()
            ->each(function (Product $product) use ($tags): void {
                $product->tags()->attach(
                    $tags->random(mt_rand(1, 3))->pluck('id')
                );
            });
    }
}
