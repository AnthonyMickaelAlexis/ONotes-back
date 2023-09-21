<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SubCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(3);
        return [
            'name' => $title,
            'slug' => Str::slug($title),
            'category_id' => fake()->randomNumber(),
            'created_at' => fake()->date(),
            'updated_at' => fake()->date(),
        ];
    }
}
