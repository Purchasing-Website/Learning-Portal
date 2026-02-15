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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->text('description')->nullable();
            $table->string('image')->nullable(); // background image or banner
            $table->boolean('is_active')->default(true); // true = active, false = inactive

            // Quiz relationship
            $table->unsignedBigInteger('quiz_id')->nullable(); // linked final quiz
            $table->integer('pass_score')->default(80); // percentage required to pass

            // Audit fields
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps(); // created_at, updated_at

            // Foreign keys
            $table->unsignedBigInteger('tier_id')->nullable();
            $table->foreign('tier_id')->references('id')->on('tiers')->nullOnDelete();
            $table->foreign('quiz_id')->references('id')->on('quizzes')->nullOnDelete();
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
        Schema::dropIfExists('classes');
    }
};
