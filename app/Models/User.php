<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected static function newFactory()
    {
        return \Database\Factories\UserFactory::new();
    }

    protected $fillable = [
        'company_id',
        'branch_id',
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'is_active' => 'boolean',
        ];
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function uploadedDocuments()
    {
        return $this->hasMany(EmployeeDocument::class, 'uploaded_by');
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    public function isHr(): bool
    {
        return $this->role === UserRole::HR;
    }

    public function isManager(): bool
    {
        return $this->role === UserRole::MANAGER;
    }

    public function canAccessCompany(?int $companyId): bool
    {
        return $this->isAdmin() || ($companyId !== null && $this->company_id === $companyId);
    }

    public function canAccessBranch(?int $branchId): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($branchId === null) {
            return false;
        }

        return $this->isHr()
            ? $this->company_id === Branch::query()->whereKey($branchId)->value('company_id')
            : $this->branch_id === $branchId;
    }
}
