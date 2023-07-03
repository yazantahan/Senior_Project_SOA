<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'Question',
        'Difficulty_flag',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function Teacher():BelongsTo {
        return $this->belongsTo(Teacher::class);
    }

    public function Admin():BelongsTo {
        return $this->belongsTo(Admin::class);
    }

    public function Category():BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function CorrectAns():HasMany {
        return $this->HasMany(CorrectAns::class);
    }

    public function getCorrectAns(): hasMany {
        return $this->HasMany(CorrectAns::class)->inRandomOrder();
    }

    public function WrongAns():HasMany
    {
        return $this->hasMany(WrongAns::class);
    }

    public function getWrongAns():HasMany {
        return $this->hasMany(WrongAns::class)->inRandomOrder();
    }

    public function Exams():belongstoMany {
        return $this->belongsToMany(Exam::class, 'question_exam_pivot')
            ->withPivot('id', 'is_correct', 'choosed_Ans');
    }

    public function FinalExams():belongsToMany {
        return $this->belongsToMany(FinalExam::class, 'final_exam_question_pivot')
            ->withPivot('id', 'is_correct', 'choosed_Ans');
    }
}
