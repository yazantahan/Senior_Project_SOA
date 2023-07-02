<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function Questions():hasMany {
        return $this->hasMany(Question::class);
    }

    public function randQuestions():hasMany {
        return $this->hasMany(Question::class)
            ->whereHas('CorrectAns')
            ->whereHas('WrongAns')
            ->inRandomOrder()->limit(30);
    }

    public function Teachers():hasMany {
        return $this->hasMany(Category::class);
    }
}
