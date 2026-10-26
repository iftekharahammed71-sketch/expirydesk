<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReminderLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_document_id',
        'lead_days',
        'recipient_emails',
        'sent_at',
        'status',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'recipient_emails' => 'array',
            'sent_at' => 'datetime',
        ];
    }

    public function employeeDocument()
    {
        return $this->belongsTo(EmployeeDocument::class);
    }
}
