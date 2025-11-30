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
        Schema::create('student_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('class_id')->nullable()->constrained('classes');
            $table->foreignId('lesson_id')->nullable()->constrained('lessons');
            $table->decimal('progress_percentage', 5, 2)->default(0.00); // e.g. 75.50%
            $table->boolean('is_completed')->default(false);
            $table->timestamp('last_accessed_at')->nullable();
            $table->timestamps();
            $table->unique(['student_id','lesson_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_progress');
    }
};
