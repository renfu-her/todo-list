<?php

namespace Database\Seeders;

use App\Models\Priority;
use Illuminate\Database\Seeder;

class PrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $priorities = [
            ['name' => 'Low', 'color' => '#6B7280', 'level' => 1],
            ['name' => 'Medium', 'color' => '#F59E0B', 'level' => 2],
            ['name' => 'High', 'color' => '#EF4444', 'level' => 3],
            ['name' => 'Urgent', 'color' => '#DC2626', 'level' => 4],
        ];

        foreach ($priorities as $priority) {
            Priority::create($priority);
        }
    }
}
