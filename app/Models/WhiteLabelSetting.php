<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhiteLabelSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'company_display_name',
        'logo_path',
        'brand_color',
        'mail_from_name',
        'mail_from_address',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
