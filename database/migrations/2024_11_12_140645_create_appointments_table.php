<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id('appointment_id');
            $table->foreignId('patient_id')->constrained('patients', 'patient_id');
            $table->foreignId('doctor_id')->constrained('doctors', 'doctor_id');
            $table->foreignId('schedule_id')->constrained('doctor_schedules', 'schedule_id');
            $table->datetime('appointment_date');
            $table->enum('status', ['PENDING', 'CONFIRMED', 'CANCELLED', 'COMPLETED']);
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_rescheduled')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};