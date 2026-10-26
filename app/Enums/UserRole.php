<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case HR = 'hr';
    case MANAGER = 'manager';

    public function label(): string
    {
        return __('roles.'.$this->value);
    }

    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }

    public function isHr(): bool
    {
        return $this === self::HR;
    }

    public function isManager(): bool
    {
        return $this === self::MANAGER;
    }
}
