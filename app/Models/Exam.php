<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_marks'
    ];

    public function Questions():BelongsToMany {
        return $this->belongsToMany(Question::class, 'question_exam_pivot')->using(QuestionExam::class)
            ->withPivot('choosed_Ans', 'is_correct');
    }

    public function User():BelongsTo {
        return $this->belongsTo(User::class);
    }
}
