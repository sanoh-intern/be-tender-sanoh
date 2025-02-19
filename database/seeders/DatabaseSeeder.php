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
        Role::factory()->createMany([
            ['role_tag' => 'super-admin'],
            ['role_tag' => 'admin-purchasing'],
            ['role_tag' => 'admin-review'],
            ['role_tag' => 'admin-presdir'],
            ['role_tag' => 'supplier'],
        ]);

        User::factory()->create([
            'role_id' => 1,
            'email' => '1@gmail.com',
        ]);

        User::factory()->create([
            'role_id' => 2,
            'email' => '2@gmail.com',
        ]);

        User::factory()->create([
            'role_id' => 3,
            'email' => '3@gmail.com',
        ]);

        User::factory()->create([
            'role_id' => 4,
            'email' => '4@gmail.com',
        ]);

        User::factory()->create([
            'role_id' => 5,
            'email' => '5@gmail.com',
        ]);


        // Will be used in future
        // Attach roles to the user (many-to-many)
        // $user->role()->attach([
        //     $role[0]->id, // super-admin role
        //     $role[1]->id, // admin-purchasing role
        //     $role[2]->id, // admin-review role
        //     $role[3]->id, // admin-presdir role
        //     $role[4]->id, // supplier role
        // ]);
    }
}
