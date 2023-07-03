<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FinalExam extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_marks'
    ];

    public function Questions():BelongsToMany {
        return $this->BelongsToMany(Question::class, 'final_exam_question_pivot')->using(FinalExamQuestion::class)
            ->withPivot('choosed_Ans', 'is_correct','final_exam_id', 'question_id');
    }

    public function User():BelongsTo {
        return $this->belongsTo(User::class);
    }
}
