<?php
    
namespace App\Controllers;

use App\Models\BasePrice;
use App\Models\Product;
use App\Common\Message;

class BasePriceController extends Controller {
    
    public function index($req, $res) {
        return $this->view->render($res, 'base-price/index.twig', [
           'base_prices' => BasePrice::get() 
        ]);
    }

    public function create($req, $res) {
        return $this->view->render($res, 'base-price/create.twig', [
            'products' => Product::orderBy('full_name')->get()
        ]);
    }
 
    public function store($req, $res) {
        $productId = $req->getParam('product_id');
        $price = $req->getParam('price');
        $product = Product::find($productId);
        $isExists = BasePrice::where('product_id', $productId)->count();
        
        if ($isExists) {
            $this->flash->addMessage('error', Message::dataExists($product->full_name));
            return $res->withRedirect($this->router->pathFor('base.price.index'));
        }

        BasePrice::create([
            'product_id' => $productId,
            'price' => $price
        ]);
        
        $this->flash->addMessage('success', Message::dataCreated($product->full_name));
       return $res->withRedirect($this->router->pathFor('base.price.index'));

    }
 
    public function edit($req, $res, $args) {
        return $this->view->render($res, 'base-price/update.twig', [
            'base_price' => BasePrice::find($args['id']),
        ]);
    }
   
    public function update($req, $res) {
        $basePrice = BasePrice::find($req->getParam('id'));
        
        $basePrice->update([
            'price' => $req->getParam('price'),
        ]);
 
        $this->flash->addMessage('success', Message::dataUpdated($basePrice->product->full_name));
        return $res->withRedirect($this->router->pathFor('base.price.index'));
    }

    public function actuality($req, $res) {
        $basePrice = BasePrice::find($req->getParam('id'));

        $basePrice->update([
            'is_actual' => $basePrice->is_actual ? false : true,
        ]);

        $this->flash->addMessage('success', Message::dataUpdated($basePrice->product->full_name));
        
        return $res->withRedirect($this->router->pathFor('base.price.index'));
    }

}