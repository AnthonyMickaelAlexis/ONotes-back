<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\SubCategory;
use App\Models\Tag;
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
            'subtitle' => fake()->sentence(2),
            'slug' => Str::slug($title),
            'text_content' => $content,
            'file_content' => fake()->name,
            'banner' => fake()->imageUrl,
            'user_id' => fake()->randomNumber(),
            'subcategory_id' => function () {
                return SubCategory::factory()->create()->id;
            },
            'created_at' => fake()->date(),
            'updated_at' => fake()->date(),
        ];
    }

    public function configure(): ArticleFactory
    {
        return $this->afterCreating(function (Article $article) {
            $article->tag()->attach(Tag::factory()->count(3)->create());
        });
    }
}
