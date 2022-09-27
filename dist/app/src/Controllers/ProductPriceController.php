<?php
    
namespace App\Controllers;

use App\Models\BasePrice;
use App\Models\LogisticPrice;
use App\Models\ProductPrice;
use App\Common\JSON;
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

    public function update($req, $res) {
        $params = $req->getParams();

        foreach($params as $key => $value) {
            $item = explode('-', $key);
            $tablePrefix = $item[0];
            if ($item[0] == 'base' || $item[0] == 'logistic') {
                $id = $item[2];
                $entity = $item[0] == 'base' ? 
                    BasePrice::where('product_id', $id)->first() :
                    LogisticPrice::where('place_id', $id)->first(); 
                $entity->update(['price' => $value]);    
            }
        }

        ProductPrice::rebuild();
        
        return $res->withRedirect($this->router->pathFor('home.index'));
    }

    public function json($req, $res) {
        $arr = ProductPrice::arr();
        JSON::body($res, $arr);
        return JSON::header($res); 

    }

}