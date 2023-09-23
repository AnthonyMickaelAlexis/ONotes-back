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


        $user = User::factory()->create();
        Article::factory()->count(3)->for($user)->create();
        // $users = User::factory()->count(10)->create();
        // foreach ($users as $user) {
        //     Article::factory()->count(3)->for($user)->create();
        // }
        // Article::factory()->count(3)->forUser()->create();
    }
}
