<?php

namespace App\Models;
use \Illuminate\Database\Eloquent\Model;

class ProductTranslate extends Model {
    protected $table = 'product_translates';
    
    protected $fillable = [
        'product_id',
        'language_id',
        'full_name',
        'is_actual',
    ];

    public function language() {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
    
}