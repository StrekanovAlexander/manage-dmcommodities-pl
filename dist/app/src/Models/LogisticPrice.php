<?php

namespace App\Models;
use \Illuminate\Database\Eloquent\Model;

class LogisticPrice extends Model {
    protected $table = 'logistic_prices';
    
    protected $fillable = [
        'place_id',
        'price',
        'is_actual',
    ];

    public function place() {
        return $this->belongsTo(Place::class, 'place_id');
    }
    
}