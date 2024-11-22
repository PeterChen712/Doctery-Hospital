<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id('appointment_id');
            $table->foreignId('patient_id')->constrained('patients', 'patient_id')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('doctors', 'doctor_id')->onDelete('cascade');
            $table->foreignId('schedule_id')->constrained('schedules', 'schedule_id')->onDelete('cascade');
            $table->datetime('appointment_date');
            $table->time('appointment_time'); // add
            $table->enum('status', ['PENDING', 'CONFIRMED', 'CANCELLED', 'COMPLETED'])->default('PENDING');
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            $table->text('symptoms')->nullable();
            $table->boolean('is_rescheduled')->default(false);
            $table->timestamps();

            // Add indexes
            $table->index('appointment_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};