<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorrectAns extends Model
{
    use HasFactory;

    protected $fillable = [
        'Ans'
    ];

    public function Question() {
        return $this->belongsTo(Question::class);
    }
}
