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
        Schema::create('final_exam_question_pivot', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_correct');
            $table->string('choosed_Ans');

            $table->unsignedBigInteger('final_exam_id')->nullable();
            $table->unsignedBigInteger('question_id')->nullable();

            $table->foreign('final_exam_id')->references('id')->on('final_exams');
            $table->foreign('question_id')->references('id')->on('questions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('final_exam_question_pivot');
    }
};
