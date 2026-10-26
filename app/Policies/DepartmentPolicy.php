<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;

class DepartmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isHr();
    }

    public function view(User $user, Department $department): bool
    {
        return $user->isAdmin() || ($user->company_id === $department->company_id);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Department $department): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Department $department): bool
    {
        return $this->update($user, $department);
    }
}
