<?php

namespace App\Models;
use \Illuminate\Database\Eloquent\Model;

class User extends Model {
    protected $table = 'users';
    
    protected $fillable = [
        'user_name',
        'password',
        'is_actual',
    ];

}
