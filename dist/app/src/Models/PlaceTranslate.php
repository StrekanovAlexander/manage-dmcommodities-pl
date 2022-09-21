<?php

namespace App\Models;
use \Illuminate\Database\Eloquent\Model;

class PlaceTranslate extends Model {
    protected $table = 'place_translates';
    
    protected $fillable = [
        'place_id',
        'language_id',
        'is_actual',
    ];

}