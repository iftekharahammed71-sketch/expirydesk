<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return \Database\Factories\CompanyFactory::new();
    }

    protected $fillable = [
        'name',
        'slug',
        'status',
        'timezone',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function documentTypes()
    {
        return $this->hasMany(DocumentType::class);
    }

    public function whiteLabelSettings()
    {
        return $this->hasOne(WhiteLabelSetting::class);
    }

    public function reminderSettings()
    {
        return $this->hasMany(ReminderSetting::class);
    }
}
