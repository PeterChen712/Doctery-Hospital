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
        Schema::create('medical_record_medicines', function (Blueprint $table) {
            $table->foreignId('medical_record_id')->constrained('medical_records', 'record_id');
            $table->foreignId('medicine_id')->constrained('medicines', 'medicine_id');
            $table->integer('quantity');
            $table->string('dosage');
            $table->text('instructions');
            $table->timestamps();
            $table->primary(['medical_record_id', 'medicine_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_record_medicines');
    }
};
