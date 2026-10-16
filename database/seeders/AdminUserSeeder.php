<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrNew(['email' => 'admin@smartomoro.com']);

        if (! $user->exists) {
            $user->forceFill([
                'name' => 'Administrator',
                'password' => 'password',
                'email_verified_at' => now(),
            ]);
        }

        $user->forceFill(['role' => 'administrator'])->save();
    }
}
