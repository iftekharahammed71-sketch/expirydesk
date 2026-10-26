<?php

namespace App\Models;

use App\Enums\EmployeeStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return \Database\Factories\EmployeeFactory::new();
    }

    protected $fillable = [
        'company_id',
        'branch_id',
        'department_id',
        'employee_number',
        'first_name',
        'last_name',
        'email',
        'phone',
        'nationality',
        'job_title',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => EmployeeStatus::class,
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

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function documents()
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name.' '.$this->last_name);
    }
}
