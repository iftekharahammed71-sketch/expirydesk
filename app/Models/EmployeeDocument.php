<?php

namespace App\Models;

use App\Enums\DocumentStatus;
use App\Services\DocumentStatusService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class EmployeeDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'document_type_id',
        'document_number',
        'issue_date',
        'expiry_date',
        'file_path',
        'original_filename',
        'mime_type',
        'file_size',
        'uploaded_by',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'expiry_date' => 'date',
        ];
    }

    protected $appends = ['status', 'status_label', 'status_badge_class'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function reminderLogs()
    {
        return $this->hasMany(ReminderLog::class);
    }

    public function getStatusAttribute(): DocumentStatus
    {
        return app(DocumentStatusService::class)->forDate($this->expiry_date ?? Carbon::today());
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status->label();
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return $this->status->badgeClass();
    }
}
