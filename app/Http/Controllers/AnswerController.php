<?php

namespace App\Http\Controllers;

use App\Models\CorrectAns;
use App\Models\Question;
use App\Models\WrongAns;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexCorrectAns($id)
    {
        $question = Question::find($id);

        $CorrectAns = CorrectAns::whereHas('Question', function ($query) use ($question) {
            $query->where('id', $question->id);
        })->get();

        return response()->json(['Correct Answers' => $CorrectAns], 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function indexWrongAns($id)
    {
        $Question = Question::find($id);

        $WrongAns = WrongAns::whereHas('Question', function ($query) use ($Question) {
            $query->where('id', $Question->id);
        })->get();

        return response()->json(['Wrong Answers' => $WrongAns], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeCorrectAns(Request $request)
    {
        $validate = $request->validate([
            'Answer' => 'required',
            'Ques_id' => 'required|int'
        ]);

        if ($validate) {
            $Question = Question::find($request->Ques_id);

            $CorrectAns = new CorrectAns();
            $CorrectAns->Answer = $request->Answer;
            $CorrectAns->Question()->associate($Question);

            $CorrectAns->save();

            return response()->json(['Message'=>'The correct Answer added Successfully!', 'Correct Answer'=>$CorrectAns], 200);
        }

        return response()->json(['Message'=>'failed'], 300);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeWrongAns(Request $request)
    {
        $validate = $request->validate([
            'Answer' => 'required',
            'Ques_id' => 'required|int'
        ]);

        if ($validate) {
            $Question = Question::find($request->Ques_id);

            $WrongAns = new WrongAns();
            $WrongAns->Answer = $request->Answer;
            $WrongAns->Question()->associate($Question);

            $WrongAns->save();

            return response()->json(['Message'=>'The Wrong Answer added Successfully!', 'Wrong Answer'=>$WrongAns], 200);
        }

        return response()->json(['Message'=>'failed'], 300);
    }

    /**
     * Display the specified resource.
     */
    public function showCorrectAns(string $id)
    {
        $answer = CorrectAns::find($id);

        return response()->json(['Correct Answer' => $answer], 200);
    }

    /**
     * Display the specified resource.
     */
    public function showWrongAns(string $id)
    {
        $answer = WrongAns::find($id);

        return response()->json(['Wrong Answer' => $answer], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateCorrectAns(Request $request, string $id)
    {
        $validate = $request->validate([
            'Answer' => 'required'
        ]);

        if ($validate) {
            $answer = CorrectAns::find($id);

            $answer->Answer = $request->Answer;

            $answer->save();

            return response()->json(['message' => 'Answer successfully updated!'], 200);
        }

        return response()->json(['message' => 'failed', 300]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateWrongAns(Request $request, string $id)
    {
        $validate = $request->validate([
            'Answer' => 'required'
        ]);

        if ($validate) {
            $answer = WrongAns::find($id);

            $answer->Answer = $request->Answer;

            $answer->save();

            return response()->json(['message' => 'Answer successfully updated!'], 200);
        }

        return response()->json(['message' => 'failed', 300]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyCorrectAns(Request $request)
    {
        $Answer = CorrectAns::destroy($request->id);

        return response()->json(['message'=>'Answer Deleted Successfully!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyWrongAns(Request $request)
    {
        $Answer = WrongAns::destroy($request->id);

        return response()->json(['message'=>'Answer Deleted Successfully!'], 200);
    }
}
