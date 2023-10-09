<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->has(Article::factory()->count(10))
            ->create([
                'lastname' => 'toto',
                'firstname' => 'tartato',
                'pseudo' => 'toutipotile',
                'email' => 'toto@mail.com',
                'email_verified_at' => now(),
                'password' => 'toto'
            ]);

    }
}
