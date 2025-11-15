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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            // Relationship
            $table->unsignedBigInteger('quiz_id'); // FK to quizzes

            $table->text('question'); // actual question
            $table->enum('questiontype', ['single', 'multiple', 'true_false'])
                  ->default('single'); // supports different question types

            $table->text('source_url')->nullable(); // can store file path or streaming link

            // Optional fields for advanced usage
            //$table->text('explanation')->nullable(); // feedback or reasoning shown after answering
            $table->integer('points')->default(1); // weight of question

            $table->boolean('is_active')->default(true);

            // Audit fields
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('quiz_id')->references('id')->on('quizzes')->cascadeOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
