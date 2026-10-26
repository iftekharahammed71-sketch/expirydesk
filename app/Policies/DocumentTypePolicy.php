<?php

namespace App\Policies;

use App\Models\DocumentType;
use App\Models\User;

class DocumentTypePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isHr() || $user->isManager();
    }

    public function view(User $user, DocumentType $type): bool
    {
        return $user->isAdmin() || $type->company_id === null || $user->company_id === $type->company_id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, DocumentType $type): bool
    {
        return $user->isAdmin() && ! $type->is_system;
    }

    public function delete(User $user, DocumentType $type): bool
    {
        return $this->update($user, $type);
    }
}
