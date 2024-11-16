<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('doctor_id')->constrained('users', 'user_id');
            $table->foreignId('patient_id')->constrained('users', 'user_id');
            $table->foreignId('record_id')->constrained('medical_records', 'record_id'); // Changed from medical_record_id
            $table->text('notes')->nullable();
            $table->enum('status', ['PENDING', 'PROCESSING', 'COMPLETED', 'CANCELLED'])->default('PENDING');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            // Add indexes
            $table->index('doctor_id');
            $table->index('patient_id');
            $table->index('record_id'); // Updated index
            $table->index('status');
        });

        // Create prescription_medicines pivot table
        Schema::create('prescription_medicines', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('prescription_id')->constrained('prescriptions')->onDelete('cascade');
            $table->foreignId('medicine_id')->constrained('medicines', 'medicine_id');
            $table->string('dosage');
            $table->string('frequency');
            $table->string('duration');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescription_medicines');
        Schema::dropIfExists('prescriptions');
    }
};