<?php

namespace App\Enums;

enum DocumentStatus: string
{
    case VALID = 'valid';
    case EXPIRING_SOON = 'expiring_soon';
    case EXPIRED = 'expired';

    public function label(): string
    {
        return __('documents.statuses.'.$this->value);
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::VALID => 'badge-success',
            self::EXPIRING_SOON => 'badge-warning',
            self::EXPIRED => 'badge-danger',
        };
    }
}
