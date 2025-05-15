<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::insert([
            ['title' => 'A', 'user_id' => 1],
            ['title' => 'B', 'user_id' => 1],
            ['title' => 'C', 'user_id' => 1],
        ]);        
    }
}
