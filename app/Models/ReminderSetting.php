<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReminderSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'days_before',
        'is_enabled',
    ];

    protected function casts(): array
    {
        return [
            'days_before' => 'integer',
            'is_enabled' => 'boolean',
        ];
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
