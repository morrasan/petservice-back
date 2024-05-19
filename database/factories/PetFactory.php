<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pet>
 */
class PetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['available', 'pending', 'sold'];

        return [
            'name' => fake()->name(),
            'photo_urls' => [Str::random(50)],
            'category_id' => Category::factory(),
            'status' => fake()->randomElement($statuses),
        ];
    }
}
