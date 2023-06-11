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
            $table->String("Question");
            $table->integer("Difficulty_flag");

            if (Schema::hasTable('teachers') && Schema::hasTable('categories')) {
                $table->unsignedBigInteger('category_id');
                $table->unsignedBigInteger('teacher_id')->nullable();
                $table->unsignedBigInteger('admin_id')->nullable();

                $table->foreign('teacher_id')->references('id')->on('teachers');
                $table->foreign('admin_id')->references('id')->on('admins');
                $table->foreign('category_id')->references('id')->on('categories');
            }

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_question');
    }
};
