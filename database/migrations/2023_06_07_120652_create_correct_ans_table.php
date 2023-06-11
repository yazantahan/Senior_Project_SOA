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
        Schema::create('correct_ans', function (Blueprint $table) {
            $table->id();
            $table->String("Answer")->unique();

            if (Schema::hasTable('questions')) {
                $table->unsignedBigInteger('question_id');
                $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
            }

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('correct_ans');
    }
};
