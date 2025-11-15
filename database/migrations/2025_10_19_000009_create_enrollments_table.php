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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            // --- Foreign keys ---
            $table->foreignId('student_id')
                  ->constrained('users')
                  ->onDelete('cascade'); // Delete enrollment if student deleted

            $table->foreignId('course_id')
                  ->nullable()
                  ->constrained('courses')
                  ->nullOnDelete(); // Delete enrollment if class deleted
            $table->unsignedBigInteger('class_id');
            $table->foreign('class_id')
                  ->references('id')
                  ->on('classes')
                  ->onDelete('cascade'); // Delete enrollment if class deleted
            // --- Progress tracking ---
            $table->enum('status', ['active', 'completed', 'dropped'])
                  ->default('active');
            $table->decimal('progress', 5, 2)->default(0.00); // percentage (0.00 - 100.00)
            $table->timestamp('enrolled_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            // --- Audit columns ---
            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->foreignId('updated_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
