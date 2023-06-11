<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticate;

use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class admin extends Authenticate implements JWTSubject
{
    use Notifiable, hasFactory;

    protected $fillable = [
      "name",
      "password"
    ];

    protected $hidden = [
        "remember_token",
        "password"
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
