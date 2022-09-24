<?php
    
namespace App\Controllers;

use App\Models\LogisticPrice;
use App\Models\Place;
// use App\Models\ProductTranslate;
use App\Common\Message;

class LogisticPriceController extends Controller {
    
    public function index($req, $res) {
        return $this->view->render($res, 'logistic-price/index.twig', [
           'logistic_prices' => LogisticPrice::get() 
        ]);
    }

    public function create($req, $res) {
        return $this->view->render($res, 'logistic-price/create.twig', [
            'places' => Place::orderBy('full_name')->where('is_actual', true)->get()
        ]);
    }
 
    public function store($req, $res) {
        $placeId = $req->getParam('place_id');

        if (!$placeId) {
            $this->flash->addMessage('error', Message::dataEmpty());
            return $res->withRedirect($this->router->pathFor('logistic.price.create'));
        }
                
        $price = $req->getParam('price');
        $place = Place::find($placeId);
        $isExists = LogisticPrice::where('place_id', $placeId)->count();
        
        if ($isExists) {
            $this->flash->addMessage('error', Message::dataExists($place->full_name));
            return $res->withRedirect($this->router->pathFor('logistic.price.create'));
        }

        LogisticPrice::create([
            'place_id' => $placeId,
            'price' => $price
        ]);
        
        $this->flash->addMessage('success', Message::dataCreated($place->full_name));
        return $res->withRedirect($this->router->pathFor('logistic.price.index'));

    }
 
    public function edit($req, $res, $args) {
        return $this->view->render($res, 'logistic-price/update.twig', [
            'logistic_price' => LogisticPrice::find($args['id']),
        ]);
    }
   
    public function update($req, $res) {
        $logisticPrice = LogisticPrice::find($req->getParam('id'));
        
        $logisticPrice->update([
            'price' => $req->getParam('price'),
        ]);
 
        $this->flash->addMessage('success', Message::dataUpdated($logisticPrice->place->full_name));
        return $res->withRedirect($this->router->pathFor('logistic.price.index'));
    }

    public function actuality($req, $res) {
        $logisticPrice = LogisticPrice::find($req->getParam('id'));

        $logisticPrice->update([
            'is_actual' => $logisticPrice->is_actual ? false : true,
        ]);

        return $res->withRedirect($this->router->pathFor('logistic.price.index'));
    }

//     public function json($req, $res) {
//         $json = [];
//         $basePrices = BasePrice::where('is_actual', true)->get();
// 
//         foreach($basePrices as $basePrice) {
//             $json[] = [
//                 'id' => $basePrice->product_id,
//                 'title' => $basePrice->product->full_name,
//                 'price' => $basePrice->price,
//                 'translates' => $this->productTranslates($basePrice)    
//             ];         
//         } 
// 
//         $res->getBody()->write(json_encode($json, JSON_UNESCAPED_UNICODE));
//         
//         return  $res->withHeader('Content-type', 'application/json; charset=utf-8')
//             ->withHeader('Access-Control-Allow-Origin', '*')
//             ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
//             ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
//    }
// 
//     private function productTranslates($basePrice) {
//         $productTranslates = ProductTranslate::where('is_actual', true)
//             ->where('product_id', $basePrice->product_id)->get();
//         $translates = [];
//         foreach($productTranslates as $productTranslate) {    
//             $translates[] = [
//                 'language' => $productTranslate->language->short_name,
//                 'title' => $productTranslate->full_name
//             ];
//         } 
//         return $translates;
//     }          

}