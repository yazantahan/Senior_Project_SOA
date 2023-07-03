<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class FinalExamQuestion extends Pivot
{
    use HasFactory;

    protected $table = 'final_exam_question_pivot';
    public $timestamps = false;

    protected $fillable = [
        'question_id',
        'final_exam_id',
        'is_correct',
        'choosed_Ans'
    ];
}
