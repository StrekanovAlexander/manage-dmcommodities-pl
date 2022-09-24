<?php

namespace App\Models;
use \Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model {
    protected $table = 'product_prices';
    
    protected $fillable = [
        'product_id',
        'place_id',
        'price',
        'is_actual'
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function place() {
        return $this->belongsTo(Place::class, 'place_id');
    }

    public static function list() {
        return self::query()
            ->join('base_prices', 'product_prices.product_id', '=', 'base_prices.product_id')
            ->join('logistic_prices', 'product_prices.place_id', '=', 'logistic_prices.place_id')
            ->get([
                'product_prices.*', 
                'base_prices.price as base_price',
                'logistic_prices.price as logistic_price'
            ]);
    }
    
}