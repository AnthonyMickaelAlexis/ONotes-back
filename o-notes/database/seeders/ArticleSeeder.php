<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\User;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $users = User::factory()->count(10)->create();
         foreach ($users as $user) {
            Article::factory()->count(random_int(3, 8))->for($user)->create();
         }
    }
}
