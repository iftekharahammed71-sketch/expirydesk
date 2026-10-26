<?php

namespace App\Services;

use App\Enums\DocumentStatus;
use Illuminate\Support\Carbon;

class DocumentStatusService
{
    public function forDate(Carbon $expiryDate, ?Carbon $today = null): DocumentStatus
    {
        $today ??= Carbon::today();

        if ($expiryDate->lt($today)) {
            return DocumentStatus::EXPIRED;
        }

        if ($expiryDate->lte($today->copy()->addDays(30))) {
            return DocumentStatus::EXPIRING_SOON;
        }

        return DocumentStatus::VALID;
    }
}
