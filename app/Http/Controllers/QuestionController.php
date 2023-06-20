<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Question;
use App\Models\teacher;
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
        $validatedData = $request->validate([
            'question' => 'required',
            'difficulty_flag' => 'required|int',
            'cate_id' => 'required|int'
        ]);

        if ($validatedData) {
            $user = auth()->user();

            if ($user->getTable() === 'admins' || $user->getTable() === 'teachers') {
                $question = new Question();
                $category = Category::find($request->cate_id);

                $question->Question = $request->question;
                $question->Difficulty_flag = $request->difficulty_flag;
                $question->Category()->associate($category);

                if ($user->getTable() === 'admins') {
                    $question->Admin()->associate($user);
                } else {
                    $question->Teacher()->associate($user);
                }

                $question->save();

                return response()->json(["Message"=>"Question successfully Added!", "Question" => $question], 201);
            }
        }

        return response()->json(["Message" => "Failed to add question."], 422);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Question = Question::find($id);

        return response()->json(['Question' => $Question], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $Question = Question::findorFail($id);

        return response()->json(['Question' => $Question], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = $request->validate([
            'question' => 'required',
            'difficulty_flag' => 'required|int'
        ]);

        if ($validate) {
            $Question = Question::find($id);

            $Question->question = $request->question;
            $Question->Difficulty_flag = $request->Difficulty_flag;

            $Question->save();

            return response()->json(['Message' => 'Question updated Successfully!', 'Question' => $Question], 200);
        }

        return response()->json(['Message' => 'failed'], 300);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Question = Question::destroy($id);

        return response()->json(['Message' => 'Question deleted Successfully!'], 200);
    }
}
