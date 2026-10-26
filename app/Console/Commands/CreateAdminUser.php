<?php

namespace App\Console\Commands;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Console\Command;

class CreateAdminUser extends Command
{
    protected $signature = 'expirydesk:create-admin
                            {--name= : Administrator display name}
                            {--email= : Administrator email address}
                            {--password= : Administrator password}';

    protected $description = 'Create or update an installation administrator';

    public function handle(): int
    {
        $name = $this->option('name') ?: $this->ask(__('commands.create_admin.name'));
        $email = $this->option('email') ?: $this->ask(__('commands.create_admin.email'));
        $password = $this->option('password') ?: $this->secret(__('commands.create_admin.password'));

        if (! is_string($name) || trim($name) === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL) || ! is_string($password) || strlen($password) < 12) {
            $this->error(__('commands.create_admin.invalid'));

            return self::INVALID;
        }

        User::updateOrCreate(['email' => $email], [
            'name' => trim($name),
            'company_id' => null,
            'branch_id' => null,
            'password' => $password,
            'role' => UserRole::ADMIN,
            'is_active' => true,
        ]);

        $this->info(__('commands.create_admin.created', ['email' => $email]));

        return self::SUCCESS;
    }
}
