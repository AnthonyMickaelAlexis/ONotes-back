<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->word();
        return [
            "name" => $name,
            "slug" => Str::slug($name),
            "user_id" => function () {
                return User::factory()->create()->id;
            },
            "logo" => fake()->imageUrl(640, 480, 'animals', true),
            "color" => fake()->hexColor(),
            "bg_color" => fake()->hexColor(),
        ];
    }
}
