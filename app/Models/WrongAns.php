<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WrongAns extends Model
{
    use HasFactory;

    protected $fillable = [
        'Answer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function Question():BelongsTo {
        return $this->belongsTo(Question::class);
    }
}
