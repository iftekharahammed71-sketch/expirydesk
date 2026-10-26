<?php

namespace App\Enums;

enum EmployeeStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case ON_LEAVE = 'on_leave';
    case TERMINATED = 'terminated';

    public function label(): string
    {
        return __('employees.statuses.'.$this->value);
    }
}
