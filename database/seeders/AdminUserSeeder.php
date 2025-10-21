<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@store.com'], // unique check
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'password' => Hash::make('mohabeh1994'), // change to secure password
                'role' => 'admin',
            ]
        );
    }
}
