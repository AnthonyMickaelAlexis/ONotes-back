<?php

namespace Database\Seeders;

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

        Article::class::factory()->count(10)->for($admin)->create();
        Tag::class::factory()->count(30)->for($admin)->create();

         $users = User::factory()->count(10)->create();
         foreach ($users as $user) {
             Article::factory()->count(random_int(3, 8))->for($user)->create();
             Tag::factory()->count(random_int(3, 8))->for($user)->create();
         }
    }
}
