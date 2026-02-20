<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create support user (hidden from users list)
        $support = User::firstOrCreate(
            ['email' => 'donia.a5ra2019@gmail.com'],
            [
                'name'               => 'support',
                'phone'              => null,
                'password'           => Hash::make('123456789'),
                'email_verified_at'  => now(),
            ]
        );

        $support->assignRole('Admin');
    }
}
