<?php
    
namespace App\Controllers;

use App\Models\Currency;
use App\Common\Message;

class CurrencyController extends Controller {
    
    public function index($req, $res) {
        return $this->view->render($res, 'currency/index.twig', [
           'currencies' => Currency::orderBy('id')->get() 
        ]);
    }

    public function create($req, $res) {
        return $this->view->render($res, 'currency/create.twig');
    }

    public function store($req, $res) {
        $shortName = trim($req->getParam('short_name'));

        if (!$shortName) {
            $this->flash->addMessage('error', Message::dataEmpty());
            return $res->withRedirect($this->router->pathFor('currency.create'));
        }

        $shortName = strtolower($shortName);
        $symbol = trim($req->getParam('symbol'));
        $isExists = Currency::where('short_name', $shortName)->count();
        
        if ($isExists) {
            $this->flash->addMessage('error', Message::dataExists($shortName));
            return $res->withRedirect($this->router->pathFor('currency.create'));
        }

        Currency::create([
            'short_name' => $shortName,
            'symbol' => $symbol,
        ]);
        
        $this->flash->addMessage('success', Message::dataCreated($shortName));
       
        return $res->withRedirect($this->router->pathFor('currency.details', [
            'id' => Currency::max('id')
        ]));
    }

    public function details($req, $res, $args) {
        $currency = Currency::find($args['id']);
        return $this->view->render($res, 'currency/details.twig', [
            'currency' => $currency,
        ]);
    }

    public function edit($req, $res, $args) {
        $currency = Currency::find($args['id']);
        return $this->view->render($res, 'currency/update.twig', [
            'currency' => $currency,
        ]);
    }
 
    public function update($req, $res) {
        $shortName = trim($req->getParam('short_name')); 
        
        if (!$shortName) {
            $this->flash->addMessage('error', Message::dataEmpty());
            return $res->withRedirect($this->router->pathFor('currency.update', [
                'id' => $req->getParam('id')
            ]));    
        }

        $shortName = strtolower($shortName);
        $symbol = trim($req->getParam('symbol'));
        $currency = Currency::find($req->getParam('id')); 
        
        $isExists = Currency::where('short_name', $shortName)->where('id', '<>', $currency->id)->count();
        
        if ($isExists) {
            $this->flash->addMessage('error', Message::dataExists($shortName));
            return $res->withRedirect($this->router->pathFor('currency.update', [
                'id' => $req->getParam('id')
            ]));       
        }

        $currency->update([
            'short_name' => $shortName,
            'symbol' => $symbol,
            'is_actual' => $req->getParam('is_actual') ? true : false,
        ]);

        $this->flash->addMessage('success', Message::dataUpdated($shortName));
        
        return $res->withRedirect($this->router->pathFor('currency.details', [
            'id' => $req->getParam('id')
        ]));
    }

    public function actuality($req, $res) {
        $currency = Currency::find($req->getParam('id'));
        $currency->update([
            'is_actual' => $currency->is_actual ? false : true,
        ]);

        $this->flash->addMessage('success', Message::dataUpdated($currency->short_name));
        
        return $res->withRedirect($this->router->pathFor('currency.details', [
            'id' => $currency->id
        ]));
    }
 
}