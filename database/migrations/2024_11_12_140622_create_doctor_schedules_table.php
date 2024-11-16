<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id('schedule_id');
            $table->foreignId('doctor_id')->constrained('doctors', 'doctor_id');
            $table->integer('day_of_week');
            $table->date('schedule_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('max_patients');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Add indexes
            $table->index('doctor_id');
            $table->index('day_of_week');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};