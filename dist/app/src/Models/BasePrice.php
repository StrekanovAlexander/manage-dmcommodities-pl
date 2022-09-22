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
    
}