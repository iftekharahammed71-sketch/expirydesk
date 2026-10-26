<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;

class EmployeePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isHr() || $user->isManager();
    }

    public function view(User $user, Employee $employee): bool
    {
        return $this->inScope($user, $employee);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isHr();
    }

    public function update(User $user, Employee $employee): bool
    {
        return ($user->isAdmin() || $user->isHr()) && $this->inScope($user, $employee);
    }

    public function delete(User $user, Employee $employee): bool
    {
        return $user->isAdmin() || ($user->isHr() && $this->inScope($user, $employee));
    }

    private function inScope(User $user, Employee $employee): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->company_id !== $employee->company_id) {
            return false;
        }

        return ! $user->isManager() || $user->branch_id === $employee->branch_id;
    }
}
