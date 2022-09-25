<?php
    
namespace App\Controllers;

use App\Models\LogisticPrice;
use App\Models\Place;
use App\Models\ProductPrice;
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

        ProductPrice::rebuild();
        
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

        ProductPrice::rebuild();
 
        $this->flash->addMessage('success', Message::dataUpdated($logisticPrice->place->full_name));
        return $res->withRedirect($this->router->pathFor('logistic.price.index'));
    }

    public function actuality($req, $res) {
        $logisticPrice = LogisticPrice::find($req->getParam('id'));

        $logisticPrice->update([
            'is_actual' => $logisticPrice->is_actual ? false : true,
        ]);

        ProductPrice::rebuild();

        return $res->withRedirect($this->router->pathFor('logistic.price.index'));
    }

}