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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id('medicine_id');
            $table->string('name');
            $table->string('description');
            $table->enum('type', ['KERAS', 'BIASA']);
            $table->decimal('price', 10, 2);
            $table->integer('stock');
            $table->string('image_url')->nullable();
            $table->boolean('is_available')->default(true);
            $table->date('expiry_date');
            $table->string('manufacturer');
            $table->string('category');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
