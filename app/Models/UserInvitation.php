<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Notifications\Notifiable;

class UserInvitation extends Model
{
    use Notifiable;

    protected $fillable = [
        'name','email','roles','token','invited_by','expires_at','accepted_at'
    ];

    protected $casts = [
        'roles' => AsArrayObject::class,
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];
}


