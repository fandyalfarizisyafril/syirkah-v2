<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_the_local_administrator(): void
    {
        $this->seed(AdminUserSeeder::class);

        $user = User::where('email', 'admin@smartomoro.com')->firstOrFail();

        $this->assertSame('Administrator', $user->name);
        $this->assertSame('administrator', $user->role);
        $this->assertNotNull($user->email_verified_at);
        $this->assertTrue(Hash::check('password', $user->password));
    }

    public function test_rerun_preserves_a_changed_password(): void
    {
        $this->seed(AdminUserSeeder::class);

        $user = User::where('email', 'admin@smartomoro.com')->firstOrFail();
        $user->forceFill(['password' => 'ChangedPassword123', 'role' => 'none'])->save();

        $this->seed(AdminUserSeeder::class);

        $user->refresh();
        $this->assertSame('administrator', $user->role);
        $this->assertTrue(Hash::check('ChangedPassword123', $user->password));
    }
}
