<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id('medicine_id'); // Make sure primary key matches foreign key
            $table->string('name');
            $table->text('description');
            $table->enum('type', ['REGULAR', 'CONTROLLED']);
            $table->integer('stock')->default(0);
            $table->decimal('price', 10, 2);
            $table->binary('image')->nullable();
            $table->date('expiry_date');
            $table->string('manufacturer');
            $table->string('category');
            $table->boolean('is_available')->default(true);
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
