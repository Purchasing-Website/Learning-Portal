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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            // Foreign Key: each lesson belongs to a class
            $table->unsignedBigInteger('class_id')->nullable();
            
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('image', 255)->nullable(); // background/banner image

            $table->unsignedInteger('sequence')->default(1); // lesson order within class

            // Lesson content source
            $table->enum('content_type', ['Document', 'Video',])->default(null);
            $table->text('source_url')->nullable(); // can store file path or streaming link

            $table->integer('duration')->nullable(); // in minutes or seconds

            // Knowledge check quiz
            $table->unsignedBigInteger('knowledge_check_id')->nullable(); // references quizzes table
            $table->decimal('pass_score', 5, 2)->default(80.00); // percentage

            // Active status
            $table->boolean('is_active')->default(true);

            // Audit fields
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps(); // created_at, updated_at

            // Foreign key relations
            $table->foreign('class_id')->references('id')->on('classes')->cascadeOnDelete();
            $table->foreign('knowledge_check_id')->references('id')->on('quizzes')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();

            //indexing
            $table->fullText('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
