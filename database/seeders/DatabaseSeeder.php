<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $adminUseer = User::factory()->create([
            'name' => 'The Admin',
            'email' => 'admin@email.com',
        ]);

        $adminUseer->assignRole('admin');
    }
}
