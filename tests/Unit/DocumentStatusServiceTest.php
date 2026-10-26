<?php

namespace Tests\Unit;

use App\Enums\DocumentStatus;
use App\Services\DocumentStatusService;
use Carbon\Carbon;
use Tests\TestCase;

class DocumentStatusServiceTest extends TestCase
{
    public function test_document_status_uses_the_thirty_day_window(): void
    {
        $service = app(DocumentStatusService::class);
        $today = Carbon::create(2026, 10, 24)->startOfDay();

        $this->assertSame(DocumentStatus::EXPIRED, $service->forDate($today->copy()->subDay(), $today));
        $this->assertSame(DocumentStatus::EXPIRING_SOON, $service->forDate($today->copy()->addDays(30), $today));
        $this->assertSame(DocumentStatus::VALID, $service->forDate($today->copy()->addDays(31), $today));
    }
}
