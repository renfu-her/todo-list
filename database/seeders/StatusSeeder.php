<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'Pending', 'color' => '#6B7280'],
            ['name' => 'In Progress', 'color' => '#3B82F6'],
            ['name' => 'On Hold', 'color' => '#F59E0B'],
            ['name' => 'Completed', 'color' => '#10B981'],
            ['name' => 'Cancelled', 'color' => '#EF4444'],
        ];

        foreach ($statuses as $status) {
            Status::create($status);
        }
    }
}
