<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);
        $content = fake()->paragraphs(3,true);
        return [
            'title' => fake()->sentence(1),
            'subtitles' => Str::slug($title),
            'text_content' => $content,
            'file_content' => fake()->name,
            'banner' => fake()->imageUrl,
            'user_id' => fake()->randomNumber(),
            'subcategory_id' => fake()->randomNumber(),
            'created_at' => fake()->date(),
            'updated_at' => fake()->date(),
        ];
    }
}
