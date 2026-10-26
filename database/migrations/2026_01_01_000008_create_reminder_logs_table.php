<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reminder_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('employee_document_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('lead_days');
            $table->json('recipient_emails');
            $table->timestamp('sent_at')->nullable();
            $table->string('status', 30)->default('sent');
            $table->text('error_message')->nullable();
            $table->timestamps();
            $table->unique(['employee_document_id', 'lead_days']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminder_logs');
    }
};
