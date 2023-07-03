<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\FinalExam;
use App\Models\FinalExamQuestion;
use App\Models\Question;
use App\Models\QuestionExam;
use App\Models\User;
use Illuminate\Http\Request;

class FinalExamController extends Controller
{
    public function index() {
        $user = User::find(Auth('users')->user()->getAuthIdentifier());
        $Finalexams = FinalExam::whereHas('User', function ($query) use ($user) {
            $query->where('id', $user->id);
        })->get();

        return response()->json(['Final exams' => $Finalexams], 200);
    }

    public function list()
    {
        $Finalexams = FinalExam::all();

        return response()->json(["Final exams" => $Finalexams],200);
    }

    public function getFinalExam($id) {
        $finalExam = FinalExam::find($id);
        $questions = $finalExam->Questions()->get();

        return response()->json(['Final Exam' => $questions], 200);
    }

    public function generateFinalExam()
    {
        $categories = Category::all();

        $exam = collect([]);
        foreach ($categories as $category) {
            $subQuestions = $category->randFinalQuestions;

            foreach ($subQuestions as $question) {
                $ans = collect([]);
                $correctAnswer = $question->getCorrectAns->take(1);
                $ans->push($correctAnswer[0]->Answer);

                $wrongAns = $question->getWrongAns->take(3);

                for ($i = 0; $i < $wrongAns->count(); $i++) {
                    $ans->push($wrongAns[$i]->Answer);
                }

                $exam->push([
                    'Question' => ['id' => $question->id, 'Question' => $question->Question],
                    'Answers' => $ans->shuffle(),
                ]);
            }
        }

        return response()->json($exam->shuffle(), 200);
    }

    public function finish(Request $request)
    {
        $final_exam_list = $request->json()->all()['finalExam'];
        $finalExam = new FinalExam();
        $finalExam->User()->associate(Auth('users')->user()->getAuthIdentifier());
        $finalExam->save();

        $total_marks = 0;

        foreach ($final_exam_list as $item) {
            $question_id = $item['Question']['id'];
            $choosed_answer = $item['choosed_Answer'];

            $question = Question::find($question_id);

            $is_correct = false;
            foreach ($question->CorrectAns as $correct_answer) {
                if ($choosed_answer == $correct_answer->Answer) {

                    $exam_id = $finalExam->id;
                    FinalExamQuestion::create([
                        'final_exam_id' => $exam_id,
                        'question_id' => $question_id,
                        'choosed_Ans' => $correct_answer->Answer,
                        'is_correct' => true
                    ]);

                    $total_marks++;
                    $is_correct = true;
                    break;
                }
            }

            if (!$is_correct) {
                foreach ($question->WrongAns as $wrong_answer) {
                    if ($choosed_answer == $wrong_answer->Answer) {

                        $exam_id = $finalExam->id;
                        FinalExamQuestion::create([
                            'final_exam_id' => $exam_id,
                            'question_id' => $question_id,
                            'choosed_Ans' => $correct_answer->Answer,
                            'is_correct' => false
                        ]);

                        break;
                    }
                }
            }
        }

        $finalExam->total_marks = $total_marks;
        $finalExam->User()->associate(Auth('users')->user()->getAuthIdentifier());
        $finalExam->save();

        return response()->json(['message' => 'Final exam added Successfully!', 'Final exam' => $finalExam], 201);
    }

}
