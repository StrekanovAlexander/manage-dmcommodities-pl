<?php
    
namespace App\Controllers;

use App\Models\Place;
use App\Models\PlaceTranslate;
use App\Common\Message;

class PlaceController extends Controller {
    
    public function index($req, $res) {
        return $this->view->render($res, 'place/index.twig', [
           'places' => Place::orderBy('full_name')->get() 
        ]);
    }

    public function create($req, $res) {
        return $this->view->render($res, 'place/create.twig');
    }

    public function store($req, $res) {
        $fullName = trim($req->getParam('full_name'));

        if (!$fullName) {
            $this->flash->addMessage('error', Message::dataEmpty());
            return $res->withRedirect($this->router->pathFor('place.create'));
        }

        $isExists = Place::where('full_name', $fullName)->count();
        
        if ($isExists) {
            $this->flash->addMessage('error', Message::dataExists($fullName));
            return $res->withRedirect($this->router->pathFor('place.create'));
        }

        Place::create([
            'full_name' => $fullName,
        ]);
        
        $this->flash->addMessage('success', Message::dataCreated($fullName));
       
        return $res->withRedirect($this->router->pathFor('place.details', [
            'id' => Place::max('id')
        ]));

    }

    public function details($req, $res, $args) {
        $place = Place::find($args['id']);
        return $this->view->render($res, 'place/details.twig', [
            'place' => $place,
            'place_translates' => PlaceTranslate::where('place_id', $place->id)->get()
        ]);
    }

    public function edit($req, $res, $args) {
        return $this->view->render($res, 'place/update.twig', [
            'place' => Place::find($args['id']),
        ]);
    }
  
    public function update($req, $res) {
        $fullName = trim($req->getParam('full_name')); 

        if (!$fullName) {
            $this->flash->addMessage('error', Message::dataEmpty());
            return $res->withRedirect($this->router->pathFor('place.update', [
                'id' => $req->getParam('id')
            ]));  
        }
        
        $place = Place::find($req->getParam('id'));
         
        $isExists = Place::where('full_name', $fullName)->where('id', '<>', $place->id)->count();
        
        if ($isExists) {
            $this->flash->addMessage('error', Message::dataExists($fullName));
            return $res->withRedirect($this->router->pathFor('place.update', [
                'id' => $req->getParam('id')
            ]));       
        }

        $place->update([
            'full_name' => $fullName,
            'is_actual' => $req->getParam('is_actual') ? true : false,
        ]);

        $this->flash->addMessage('success', Message::dataUpdated($fullName));
        
        return $res->withRedirect($this->router->pathFor('place.details', [
            'id' => $req->getParam('id')
        ]));
    }

    public function actuality($req, $res) {
        $place = Place::find($req->getParam('id'));
        $place->update([
            'is_actual' => $place->is_actual ? false : true,
        ]);

        $this->flash->addMessage('success', Message::dataUpdated($place->full_name));
        
        return $res->withRedirect($this->router->pathFor('place.details', [
            'id' => $place->id
        ]));
    }

}