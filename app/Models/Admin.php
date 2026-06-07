<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $guard = 'admin';

    protected $fillable = [
        'name', 'email', 'password', 'role', 'last_login_at'
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'last_login_at' => 'datetime',
        'password' => 'hashed',
    ];
}