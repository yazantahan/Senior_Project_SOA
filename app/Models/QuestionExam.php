<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class QuestionExam extends Pivot
{
    protected $table = 'question_exam_pivot';
    public $timestamps = false;

    protected $fillable = [
        'question_id',
        'exam_id',
        'is_correct',
        'choosed_Ans'
    ];

}
