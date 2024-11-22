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
            $table->date('schedule_date');  // Using schedule_date instead of date
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('max_patients');
            $table->integer('day_of_week');
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
