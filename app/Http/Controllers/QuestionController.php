<?php

namespace App\Http\Controllers;

use App\Http\Middleware\PreventRequestsDuringMaintenance;
use App\Models\Category;
use App\Models\CorrectAns;
use App\Models\Question;
use App\Models\teacher;
use App\Models\WrongAns;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($cate_id = null)
    {
        $info = Auth('teachers')->user();
        $teacher = teacher::find($info->getAuthIdentifier());

        if (!$teacher->Category()->get()) {
            if ($cate_id != null) {
                $Questions = Question::whereHas('Teacher', function ($query) use ($teacher) {
                    $query->where('id', $teacher->id);
                })->whereHas('Category', function ($query) use ($cate_id) {
                    $query->where('id', $cate_id);
                })->get();
            } else {
                $Questions = Question::whereHas('Teacher', function ($query) use ($teacher) {
                    $query->where('id', $teacher->id);
                })->get();
            }

            return response()->json(['Questions' => $Questions], 200);
        }

        $category = Category::find($teacher->Category()->value('id'));
        $Questions = $category->Questions()->whereHas('Teacher', function ($query) use ($teacher) {
            $query->where('id', $teacher->id);
        })->get();;

        return response()->json(['Questions' => $Questions], 200);
    }

    public function list($cate_id = null) {
        if ($cate_id != null) {
            $Questions = Category::find($cate_id)->Questions;
        } else {
            $Questions = Question::all();
        }

        return response()->json(['Questions' => $Questions], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $info = auth('teachers')->user();
        $teacher = teacher::find($info->getAuthIdentifier());
        if (!$teacher->Category()->get()) {
            $correctAns = $request->input('correctAns', []);
            $wrongAns = $request->input('wrongAns', []);

            $validatedData = $request->validate([
                'question' => 'required',
                'difficulty_flag' => 'required|int',
                'cate_id' => 'required|int',
                'correctAns' => 'required|array|size:3',
                'wrongAns' => 'required|array|size:5',
                'correctAns.*' => 'string',
                'wrongAns.*' => 'string'
            ]);

        } else {
            $correctAns = $request->input('correctAns', []);
            $wrongAns = $request->input('wrongAns', []);

            $validatedData = $request->validate([
                'question' => 'required',
                'difficulty_flag' => 'required|int',
                'correctAns' => 'required|array|size:3',
                'wrongAns' => 'required|array|size:5',
                'correctAns.*' => 'string',
                'wrongAns.*' => 'string'
            ]);
        }

        if ($validatedData) {
            $teacher = auth()->user();

            $question = new Question();

            if ($teacher->Category()->get() == null) {
                $category = Category::find($request->cate_id);
            } else {
                $category = Category::find($teacher->Category()->value('id'));
            }

            $question->Question = $request->question;
            $question->Difficulty_flag = $request->difficulty_flag;

            $question->Category()->associate($category);
            $question->Teacher()->associate($teacher);
            $question->save();

            foreach ($correctAns as $correctAnswer) {
                $correctAnswerModel = new CorrectAns();
                $correctAnswerModel->Answer =  $correctAnswer;
                $correctAnswerModel->Question()->associate($question);
                $correctAnswerModel->save();
            }

            foreach ($wrongAns as $wrongAnswer) {
                $wrongAnswerModel = new WrongAns();
                $wrongAnswerModel->Answer = $wrongAnswer;
                $wrongAnswerModel->Question()->associate($question);
                $wrongAnswerModel->save();
            }

            return response()->json(["Message"=>"Question successfully Added!",
                "Question" => $question,
                "Correct Answers" => $correctAns,
                "Wrong Answers" => $wrongAns], 201);
        }

        return response()->json(["Message" => "Failed to add question."], 422);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $correctAns = $request->input('correctAns', []);
        $wrongAns = $request->input('wrongAns', []);

        $validate = $request->validate([
            'question' => 'required',
            'difficulty_flag' => 'required|int',
            'correctAns' => 'required|array|size:3',
            'wrongAns' => 'required|array|size:5',
            'correctAns.*' => 'string',
            'wrongAns.*' => 'string'
        ]);

        if ($validate) {
            $Question = Question::find($id);

            $Question->question = $request->question;
            $Question->difficulty_flag = $request->difficulty_flag;

            $Question->save();

            $correctAnswers = $Question->CorrectAns()->get();

            for ($i = 0; $i < $correctAnswers->count(); $i++) {
                $correctAnswers[$i]->Answer = $correctAns[$i];
                $correctAnswers[$i]->save();
            }

            $wrongAnswers = $Question->WrongAns()->get();

            for ($i = 0; $i < $wrongAnswers->count(); $i++) {
                $wrongAnswers[$i]->Answer = $wrongAns[$i];
                $wrongAnswers[$i]->save();
            }

            return response()->json(['Message' => 'Question updated Successfully!',
                'Question' => $Question,
                'Correct Answers' => $correctAns,
                'Wrong Answers' => $wrongAns], 200);
        }

        return response()->json(['Message' => 'failed'], 300);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Question = Question::destroy($id);

        return response()->json(['Message' => 'Question deleted Successfully!', 'Question' => $Question], 200);
    }
}
