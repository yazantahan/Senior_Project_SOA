<?php

namespace Database\Seeders;

use App\Models\admin;
use App\Models\Category;
use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lines = file("C:\Users\yazan\OneDrive\Desktop\Questions.txt");

        $i = 0;

        foreach ($lines as $line) {
            $data = explode("?,", $line);
            $questionlin = $data[0];
            $ids = explode(",", $data[1]);
            $difficulty_flag = $ids[0];
            $category_id = $ids[1];
            $admin_id = mt_rand(1, 2);

            $category = Category::find($category_id);
            $admin = Admin::find($admin_id);

            $question = new Question();

            $question->Question = $questionlin;
            $question->Difficulty_flag = $difficulty_flag;
            $question->Category()->associate($category);
            $question->Admin()->associate($admin);
            $question->save();
        }
    }

}
