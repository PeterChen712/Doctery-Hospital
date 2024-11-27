<?php
// Create new migration:
// php artisan make:migration create_schedule_appointments_table

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('schedule_appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained('schedules', 'schedule_id')->onDelete('cascade');
            $table->foreignId('appointment_id')->constrained('appointments', 'appointment_id')->onDelete('cascade');
            $table->timestamps();
            
            // Prevent duplicate assignments
            $table->unique(['schedule_id', 'appointment_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('schedule_appointments');
    }
};