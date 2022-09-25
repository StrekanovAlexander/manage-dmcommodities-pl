<?php

namespace App\Models;
use \Illuminate\Database\Eloquent\Model;

class BasePrice extends Model {
    protected $table = 'base_prices';
    
    protected $fillable = [
        'product_id',
        'price',
        'is_actual',
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public static function actual() {
        return self::query()
            ->join('products', 'base_prices.product_id', '=', 'products.id')
            ->orderBy('products.full_name')
            ->where('base_prices.is_actual', true)
            ->get([
                'base_prices.*', 
                'products.full_name as product_name'
            ]);
    }
    
}