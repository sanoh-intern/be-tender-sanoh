<?php

namespace Database\Seeders;

use App\Models\Role;
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
        $role = Role::factory()->createMany([
            ['role_tag' => 'Supplier'],
            ['role_tag' => 'Admin-Purchasing'],
        ]);

        $user = User::factory()->create([
            'email' => 'tes@gmail.com',
        ]);

        // Attach roles to the user (many-to-many)
        $user->role()->attach([
            $role[0]->id, // Supplier role
            $role[1]->id, // Admin-Purchasing role
        ]);
    }
}
