<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use LogicException;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            throw new LogicException('Akun admin awal hanya boleh dibuat pada lingkungan local atau testing.');
        }

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
