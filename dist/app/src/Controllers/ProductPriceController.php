<?php
    
namespace App\Controllers;

use App\Models\ProductPrice;
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

    }
}