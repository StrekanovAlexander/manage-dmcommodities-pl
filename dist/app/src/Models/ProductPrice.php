<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;
use App\Models\BasePrice;
use App\Models\LogisticPrice;

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
            ->join('products', 'product_prices.product_id', '=', 'products.id')
            ->join('base_prices', 'product_prices.product_id', '=', 'base_prices.product_id')
            ->join('logistic_prices', 'product_prices.place_id', '=', 'logistic_prices.place_id')
            ->join('places', 'product_prices.place_id', '=', 'places.id')
            ->orderBy('products.full_name')
            ->orderBy('places.full_name')
            ->get([
                'product_prices.*', 
                'base_prices.price as base_price',
                'logistic_prices.price as logistic_price'
            ]);
    }

    public static function rebuild() {
        $basePrices = BasePrice::orderBy('product_id')->get();
        foreach($basePrices as $basePrice) {
            $logisticPrices = LogisticPrice::get();
            foreach($logisticPrices as $logisticPrice) {
                $productPrice = self::where('product_id', $basePrice->product_id)
                    ->where('place_id', $logisticPrice->place_id)->first();
                $price = $logisticPrice->price + $basePrice->price;
                $isActual = $logisticPrice->is_actual && $basePrice->is_actual;   
                if ($productPrice) {
                    $productPrice->update(['price' => $price, 'is_actual' => $isActual]);
                } else {    
                    self::create([
                        'product_id' => $basePrice->product_id,
                        'place_id' => $logisticPrice->place_id,
                        'price' => $price,
                        'is_actual' => $isActual
                    ]);
                }    
            }
        } 
    }
    
}