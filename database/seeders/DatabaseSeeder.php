<?php

namespace Database\Seeders;

use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);

        User::flushEventListeners();

        $admin = User::firstOrCreate(
            ['email' => 'scott@stargroup.com.mx'],
            [
                'name' => 'SCOTT',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
            ]
        );

        $admin->assignRole('admin');

        User::observe(UserObserver::class);
    }
}
