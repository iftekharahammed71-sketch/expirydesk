<?php

namespace App\Policies;

use App\Models\EmployeeDocument;
use App\Models\User;

class EmployeeDocumentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isHr() || $user->isManager();
    }

    public function view(User $user, EmployeeDocument $document): bool
    {
        return app(EmployeePolicy::class)->view($user, $document->employee);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isHr();
    }

    public function update(User $user, EmployeeDocument $document): bool
    {
        return ($user->isAdmin() || $user->isHr()) && $this->view($user, $document);
    }

    public function delete(User $user, EmployeeDocument $document): bool
    {
        return $this->update($user, $document);
    }

    public function download(User $user, EmployeeDocument $document): bool
    {
        return $this->view($user, $document);
    }
}
