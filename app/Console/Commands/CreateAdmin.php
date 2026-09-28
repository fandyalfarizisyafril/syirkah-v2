<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CreateAdmin extends Command
{
    protected $signature = 'admin:create {email} {--name=Administrator} {--role=administrator}';

    protected $description = 'Create a CMS account with a generated password';

    public function handle(): int
    {
        $values = ['email' => $this->argument('email'), 'name' => $this->option('name'), 'role' => $this->option('role')];
        $validator = Validator::make($values, ['email' => 'required|email|max:255|unique:users', 'name' => 'required|string|max:255', 'role' => 'in:administrator,editor,sales']);
        if ($validator->fails()) {
            $this->error($validator->errors()->first());

            return self::FAILURE;
        }
        $password = Str::password(20);
        $user = new User;
        $user->forceFill($values + ['password' => $password])->save();
        $this->info('Akun dibuat: '.$user->email);
        $this->line('Password: '.$password);

        return self::SUCCESS;
    }
}
