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
            $table->foreignId('doctor_id')->constrained('doctors', 'doctor_id');
            $table->foreignId('patient_id')->constrained('patients', 'patient_id');
            $table->foreignId('record_id')->constrained('medical_records', 'record_id');
            $table->text('notes')->nullable();
            $table->enum('status', ['PENDING', 'PROCESSING', 'COMPLETED', 'CANCELLED'])->default('PENDING');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
    
            $table->index(['doctor_id', 'patient_id', 'record_id', 'status']);
        });
    
        Schema::create('prescription_medicines', function (Blueprint $table) {
            $table->id();
            $table->uuid('prescription_id'); // Changed to uuid to match prescriptions table
            $table->foreignId('medicine_id')->constrained('medicines', 'medicine_id');
            $table->integer('quantity');
            $table->string('dosage');
            $table->text('instructions')->nullable();
            $table->timestamps();
    
            // Add foreign key with correct reference to uuid
            $table->foreign('prescription_id')
                ->references('id')
                ->on('prescriptions')
                ->onDelete('cascade');
        });
    }
    

    public function down(): void
    {
        Schema::dropIfExists('prescription_medicines');
        Schema::dropIfExists('prescriptions');
    }
};
