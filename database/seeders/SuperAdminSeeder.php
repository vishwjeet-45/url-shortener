<?php

namespace Database\Seeders;

use App\Models\{Role,User};
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $roles = ['SuperAdmin', 'Admin', 'Member'];

        foreach ($roles as $role) {
            Role::create([
                'name' => $role
            ]);
        }

        User::create([
            'name' => 'Super Admin',
            'email' => 'super@gmail.com',
            'password' => bcrypt('password'),
            'role_id' => Role::where('name', 'SuperAdmin')->first()->id,
        ]);
    }
}
