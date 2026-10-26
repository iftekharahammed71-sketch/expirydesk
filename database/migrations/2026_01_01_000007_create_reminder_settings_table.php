<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reminder_settings', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('days_before');
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
            $table->unique(['company_id', 'days_before']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminder_settings');
    }
};
