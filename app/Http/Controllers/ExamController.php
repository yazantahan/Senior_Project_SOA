<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Exam;
use App\Models\Question;
use App\Models\QuestionExam;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExamController extends Controller
{
    public function index() {
        $user = User::find(Auth('users')->user()->getAuthIdentifier());
        $exams = Exam::whereHas('User', function ($query) use ($user) {
            $query->where('id', $user->id);
        })->get();

        return response()->json(['Exams' => $exams], 200);
    }

    public function list()
    {
        $Exams = Exam::all();

        return response()->json(["Exams" => $Exams],200);
    }

    public function getExam($id) {
        $exam = Exam::find($id);
        $questions = $exam->Questions()->get();

        return response()->json(['Exam' => $questions], 200);
    }

    public function generateExam($cate_id)
    {
        $category = Category::findOrFail($cate_id);

        $questions = $category->randQuestions;

        $exam = collect([]);

        foreach ($questions as $question) {
            if ($question->correctAns->isEmpty() || $question->wrongAns->isEmpty()) {
                continue;
            }

            $ans = collect([]);
            $correctAnswer = $question->CorrectAns->take(1);
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

        return response()->json($exam, 200);
    }

    public function finish(Request $request)
    {
        $exam_list = $request->json()->all()[0]['exam'];
        $exam = new Exam();
        $exam->User()->associate(Auth('users')->user()->getAuthIdentifier());
        $exam->save();

        $total_marks = 0;

        foreach ($exam_list as $item) {
            $question_id = $item['Question']['id'];
            $choosed_answer = $item['choosed_Answer'];

            $question = Question::find($question_id);

            $is_correct = false;
            foreach ($question->CorrectAns as $correct_answer) {
                if ($choosed_answer == $correct_answer->Answer) {
                    $question_exam = new QuestionExam();
                    $question_exam->is_correct = true;
                    $question_exam->choosed_Ans = $correct_answer->Answer;
                    // Attach the question_exam object to the exam object
                    $exam_id = $exam->id;
                    $exam->Questions()->attach($question_exam, ['question_id' => $question_id, 'exam_id' => $exam_id, 'choosed_Ans' => $correct_answer->Answer, 'is_correct' => true]);


                    $total_marks++;
                    $is_correct = true;
                    break;
                }
            }

            if (!$is_correct) {
                foreach ($question->WrongAns as $wrong_answer) {
                    if ($choosed_answer == $wrong_answer->Answer) {
                        $question_exam = new QuestionExam();
                        $question_exam->is_correct = false;
                        $question_exam->choosed_Ans = $wrong_answer->Answer;
                        // Attach the question_exam object to the exam object
                        $exam_id = $exam->id;
                        $exam->Questions()->attach($question_exam, ['question_id' => $question_id, 'exam_id' => $exam_id, 'choosed_Ans' => $correct_answer->Answer, 'is_correct' => false]);

                        break;
                    }
                }
            }
        }

            $exam->total_marks = $total_marks;
            $exam->User()->associate(Auth('users')->user()->getAuthIdentifier());
            $exam->save();

            return response()->json(['message' => 'Exam added Successfully!', 'Exam' => $exam], 201);
        }

}
