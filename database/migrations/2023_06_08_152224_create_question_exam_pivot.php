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

            $table->string('choosed_Ans')->nullable();
            $table->boolean('is_correct')->nullable();

            if (Schema::hasTable('exams') && Schema::hasTable('questions')) {
                $table->unsignedBigInteger('exam_id')->nullable();
                $table->unsignedBigInteger('question_id')->nullable();

                $table->foreign('exam_id')->references('id')->on('exams');
                $table->foreign('question_id')->references('id')->on('questions');
            }
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
