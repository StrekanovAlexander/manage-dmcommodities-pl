<?php
    
namespace App\Controllers;

use App\Models\BasePrice;
use App\Models\LogisticPrice;
use App\Models\ProductPrice;
use App\Common\Message;

class ProductPriceController extends Controller {
    
    public function index($req, $res) {
        return $this->view->render($res, 'product-price/index.twig', [
           'product_prices' => ProductPrice::list() 
        ]);
    }

    public function refresh($req, $res) {
        self::refreshProductPrice();
        return $res->withRedirect($this->router->pathFor('product.price.index'));
    }

    public function json($req, $res) {

    }

    public static function refreshProductPrice() {
        $basePrices = BasePrice::orderBy('product_id')->get();
        foreach($basePrices as $basePrice) {
            $logisticPrices = LogisticPrice::get();
            foreach($logisticPrices as $logisticPrice) {
                $productPrice = ProductPrice::where('product_id', $basePrice->product_id)
                    ->where('place_id', $logisticPrice->place_id)->first();
                $price = $logisticPrice->price + $basePrice->price;
                $isActual = $logisticPrice->is_actual && $basePrice->is_actual;   
                if ($productPrice) {
                    $productPrice->update(['price' => $price, 'is_actual' => $isActual]);
                } else {    
                    ProductPrice::create([
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