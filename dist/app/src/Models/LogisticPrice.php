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

    public static function actual() {
        return self::query()
            ->join('places', 'logistic_prices.place_id', '=', 'places.id')
            ->orderBy('places.full_name')
            ->where('logistic_prices.is_actual', true)
            ->get([
                'logistic_prices.*', 
                'places.full_name as place_name',
            ]);
    }
    
}