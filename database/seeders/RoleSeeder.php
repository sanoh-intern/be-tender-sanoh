<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::factory()->createMany([
            ['role_tag' => 'super-admin'],
            ['role_tag' => 'purchasing'],
            ['role_tag' => 'presdir'],
            ['role_tag' => 'review'],
            ['role_tag' => 'supplier'],
        ]);
    }
}
