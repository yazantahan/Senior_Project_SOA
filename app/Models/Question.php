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

    public function WrongAns():HasMany
    {
        return $this->hasMany(WrongAns::class);
    }

    public function getNewWrongAnswers($previousWrongAnswers = [])
    {
        $wrongAnswers = $this->WrongAns;

        if (!empty($previousWrongAnswers)) {
            $wrongAnswers = $wrongAnswers->whereNotIn('id', $previousWrongAnswers->pluck('id'));
        }

        return $wrongAnswers->shuffle()->take(3);
    }

    public function Exams():belongstoMany {
        return $this->belongsToMany(Exam::class, 'question_exam_pivot')->withPivot('id', 'is_correct', 'choosed_Ans');
    }
}
