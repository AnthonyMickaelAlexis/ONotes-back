<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Article extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Article::factory()->count(10)->create();
    }
}
