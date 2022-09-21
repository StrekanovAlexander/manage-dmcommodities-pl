<?php

namespace App\Models;
use \Illuminate\Database\Eloquent\Model;

class Currency extends Model {
    protected $table = 'currencies';
    
    protected $fillable = [
        'short_name',
        'symbol',
        'is_actual',
    ];

}