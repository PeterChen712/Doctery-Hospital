<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feedback', function (Blueprint $table) {
            // Primary and Foreign Keys
            $table->id('feedback_id');
            $table->foreignId('record_id')
                  ->constrained('medical_records', 'record_id')
                  ->onDelete('cascade');
            $table->foreignId('patient_id')
                  ->constrained('patients', 'patient_id')
                  ->onDelete('cascade');
            $table->foreignId('doctor_id')
                  ->constrained('doctors', 'doctor_id')
                  ->onDelete('cascade');

            // Ratings (1-5 scale)
            $table->integer('overall_rating')
                  ->comment('Rating from 1-5');
            $table->integer('doctor_rating')
                  ->nullable()
                  ->comment('Doctor specific rating from 1-5');
            $table->integer('service_rating')
                  ->nullable()
                  ->comment('Service rating from 1-5');
            $table->integer('facility_rating')
                  ->nullable()
                  ->comment('Facility rating from 1-5');
            $table->integer('staff_rating')
                  ->nullable()
                  ->comment('Staff rating from 1-5');

            // Feedback Content
            $table->text('review')
                  ->comment('Main feedback text');
            $table->text('doctor_response')
                  ->nullable()
                  ->comment('Doctor\'s response to feedback');
            $table->text('improvement_suggestions')
                  ->nullable()
                  ->comment('Suggestions for improvement');

            // Categories and Tags
            $table->enum('category', [
                'GENERAL',
                'DOCTOR_SERVICE',
                'FACILITY',
                'STAFF_SERVICE',
                'WAIT_TIME',
                'TREATMENT_QUALITY',
                'COMMUNICATION'
            ])->default('GENERAL')
              ->comment('Feedback category');

            // Visibility and Status
            $table->boolean('is_public')
                  ->default(true)
                  ->comment('Whether feedback is publicly visible');
            $table->boolean('is_anonymous')
                  ->default(false)
                  ->comment('Whether feedback is anonymous');
            $table->enum('status', [
                'PENDING',
                'APPROVED',
                'REJECTED',
                'ARCHIVED'
            ])->default('PENDING')
              ->comment('Feedback status');

            // Admin Management
            $table->text('admin_notes')
                  ->nullable()
                  ->comment('Notes from administrators');
            $table->text('follow_up_action')
                  ->nullable()
                  ->comment('Required follow-up actions');
            $table->timestamp('reviewed_at')
                  ->nullable()
                  ->comment('When feedback was reviewed');
            $table->foreignId('reviewed_by')
                  ->nullable()
                  ->constrained('users', 'user_id')
                  ->onDelete('set null')
                  ->comment('User who reviewed the feedback');

            // Timestamps and Soft Deletes
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['doctor_id', 'created_at']);
            $table->index(['patient_id', 'created_at']);
            $table->index(['status', 'created_at']);
            $table->index('category');

            // Unique constraint
            $table->unique('record_id', 'unique_feedback_per_record');
        });

        // Add any needed full-text indexes
        DB::statement('ALTER TABLE feedback ADD FULLTEXT search(review, improvement_suggestions)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};