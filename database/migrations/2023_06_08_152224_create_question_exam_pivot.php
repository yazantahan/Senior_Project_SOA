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
        Schema::create('question_exam_pivot', function (Blueprint $table) {
            $table->id();

            $table->string('choosed_Ans');
            $table->boolean('is_correct');

            if (Schema::hasTable('exams') && Schema::hasTable('questions')) {
                $table->unsignedBigInteger('exam_id');
                $table->unsignedBigInteger('question_id');

                $table->foreign('exam_id')->references('id')->on('exams');
                $table->foreign('question_id')->references('id')->on('questions');
            }

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_exam_pivot');
    }
};
