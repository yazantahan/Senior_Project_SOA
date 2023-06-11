<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class QuestionExam extends Pivot
{
    protected $table = 'question_exam_pivot';

    protected $fillable = [
        'is_correct',
        'choosed_Ans'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
