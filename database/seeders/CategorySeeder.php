<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Work', 'color' => '#3B82F6'],
            ['name' => 'Personal', 'color' => '#10B981'],
            ['name' => 'Shopping', 'color' => '#F59E0B'],
            ['name' => 'Health', 'color' => '#EF4444'],
            ['name' => 'Education', 'color' => '#8B5CF6'],
            ['name' => 'Travel', 'color' => '#06B6D4'],
            ['name' => 'Home', 'color' => '#84CC16'],
            ['name' => 'Finance', 'color' => '#F97316'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
