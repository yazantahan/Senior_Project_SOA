<?php

namespace Database\Seeders;

use App\Models\CorrectAns;
use App\Models\Question;
use App\Models\WrongAns;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run($id = null): void
    {
        $answerLines = file("C:\Users\yazan\OneDrive\Desktop\CorrectAnswers.txt");

        foreach ($answerLines as $answerLine) {
            if ($answerLine == "\r\n") {
                continue;
            }
            $data = explode(". - ", $answerLine);
            $answer = $data[0];
            $ques_id = trim($data[1]);

            echo $ques_id;
            $wrongAns = new CorrectAns();
            $wrongAns->Answer = $answer;

            $question = Question::find($ques_id);
            $wrongAns->Question()->associate($question);

            $wrongAns->save();
        }

        $answerLines1 = file("C:\Users\yazan\OneDrive\Desktop\WrongAnswers.txt");

        foreach ($answerLines1 as $answerLine) {
            if ($answerLine == "\r\n") {
                continue;
            }
            $data = explode(". - ", $answerLine);
            $wrongAns = new wrongAns();
            $wrongAns->Answer = $data[0];
            $ques_id = trim($data[1]);

            $question = Question::find($ques_id);
            $wrongAns->Question()->associate($question);

            $wrongAns->save();
        }
    }
}
