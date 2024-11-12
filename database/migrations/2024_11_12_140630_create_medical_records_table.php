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
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id('record_id');
            $table->foreignId('patient_id')->constrained('patients', 'patient_id');
            $table->foreignId('doctor_id')->constrained('doctors', 'doctor_id');
            $table->text('symptoms');
            $table->text('diagnosis');
            $table->text('medical_action');
            $table->text('lab_results')->nullable();
            $table->datetime('treatment_date');
            $table->text('notes')->nullable();
            $table->enum('status', ['PENDING', 'IN_PROGRESS', 'COMPLETED']);
            $table->datetime('follow_up_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
