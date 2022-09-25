<?php
    
namespace App\Controllers;

use App\Models\ProductPrice;
use App\Models\LogisticPrice;
use App\Common\Message;

class ProductPriceController extends Controller {
    
    public function index($req, $res) {
        return $this->view->render($res, 'product-price/index.twig', [
           'product_prices' => ProductPrice::list() 
        ]);
    }

    public function rebuild($req, $res) {
        ProductPrice::rebuild();
        return $res->withRedirect($this->router->pathFor('product.price.index'));
    }

    public function json($req, $res) {
        $json = [];
        $logisticPrices = LogisticPrice::actual();

        foreach($logisticPrices as $logisticPrice) {
            $json[] = [
                'id' => $logisticPrice->place_id,
                'title' => $logisticPrice->place->full_name,
                'price' => $logisticPrice->price,
                'products' => $this->productPricesByPlace($logisticPrice->place_id)    
            ];         
        } 

        $res->getBody()->write(json_encode($json, JSON_UNESCAPED_UNICODE));
        
        return  $res->withHeader('Content-type', 'application/json; charset=utf-8')
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    }

    private function productPricesByPlace($placeId) {
        $productPrices = ProductPrice::actualByPlace($placeId);
        $products = [];
        
        foreach($productPrices as $productPrice) {    
            $products[] = [
                'id' => $productPrice->product_id,
                'title' => $productPrice->product_name,
                'price' => $productPrice->price
            ];
        } 

        return $products;
    }          


}