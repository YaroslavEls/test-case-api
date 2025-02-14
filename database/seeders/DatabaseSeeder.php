<?php

namespace Database\Seeders;

use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $positions = ['Lawyer', 'Content manager', 'Security', 'Designer'];

        Position::factory()
            ->count(count($positions))
            ->sequence(fn (Sequence $sequence) => ['name' => $positions[$sequence->index]])
            ->create();

        User::factory(45)->create();
    }
}
