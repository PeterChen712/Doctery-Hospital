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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id('feedback_id');
            $table->foreignId('patient_id')->constrained('patients', 'patient_id');
            $table->foreignId('doctor_id')->constrained('doctors', 'doctor_id');
            $table->foreignId('appointment_id')->constrained('appointments', 'appointment_id');
            $table->integer('rating');
            $table->text('review');
            $table->text('doctor_response')->nullable();
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
