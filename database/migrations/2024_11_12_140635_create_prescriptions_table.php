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
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id('prescription_id');
            $table->foreignId('record_id')->constrained('medical_records', 'record_id');
            $table->foreignId('medicine_id')->constrained('medicines', 'medicine_id');
            $table->integer('quantity');
            $table->text('dosage');
            $table->text('instructions');
            $table->enum('status', ['PENDING', 'PROCESSED', 'COMPLETED']);
            $table->datetime('valid_until');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};