<?php

namespace App\Models;
use \Illuminate\Database\Eloquent\Model;

class PlaceTranslate extends Model {
    protected $table = 'place_translates';
    
    protected $fillable = [
        'place_id',
        'language_id',
        'full_name',
        'is_actual',
    ];

    public function language() {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function place() {
        return $this->belongsTo(Place::class, 'place_id');
    }
    
}