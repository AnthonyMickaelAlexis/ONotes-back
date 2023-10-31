<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // créé un admin
        $admin = User::factory()->create([
            'lastname' => 'admin',
            'firstname' => 'admin',
            'pseudo' => 'admin',
            'email' => 'admin@admin.fr',
            'email_verified_at' => now(),
            'password' => 'admin', // password
            'remember_token' => Str::random(10),
            'role' => 'admin'
        ]);

        // créé 10 catégories
        $categories = Category::class::factory()->count(5)->create();

        // pour chaque catégorie, créé 2 à 5 sous-catégories
        foreach ($categories as $category) {
            SubCategory::class::factory()->count(random_int(2, 5))->for($category)->create();
        }

        //Récupère toutes les sous-catégories
        $subcategories = SubCategory::all();

        // créé 10 utilisateurs
        $users = User::factory()->count(5)->create();

        foreach ($users as $user) {
            foreach ($subcategories as $subcategory) {

                Article::factory()->count(2)->for($user)->for($subcategory)->create();
                Tag::factory()->count(2)->for($user)->create();
             }
        }
    }
}
