<?php
    
namespace App\Controllers;

use App\Models\Place;
use App\Models\PlaceTranslate;
use App\Models\Language;
use App\Common\Message;

class PlaceTranslateController extends Controller {
    
    public function create($req, $res, $args) {
        $place = Place::find($args['id']);
        return $this->view->render($res, 'place-translate/create.twig', [
            'place' => $place,
            'languages' => Language::where('is_actual', true)->get(),
        ]);
    }

    public function store($req, $res) {
        $placeId = $req->getParam('place_id');
        $languageId = $req->getParam('language_id');
        $fullName = trim($req->getParam('full_name'));

        if (!$fullName) {
            $this->flash->addMessage('error', Message::dataEmpty());
            return $res->withRedirect($this->router->pathFor('place.translate.create', [
                'id' => $placeId
            ]));
        }

        $isExists = PlaceTranslate::where('place_id', $placeId)
            ->where('language_id', $languageId)->count();
        
        if ($isExists) {
            $this->flash->addMessage('error', Message::dataRefExists());
            return $res->withRedirect($this->router->pathFor('place.translate.create', [
                'id' => $placeId
            ]));
        }

        PlaceTranslate::create([
            'place_id' => $placeId,
            'language_id' => $languageId,
            'full_name' => $fullName,
        ]);
        
        $this->flash->addMessage('success', Message::dataCreated($fullName));
       
        return $res->withRedirect($this->router->pathFor('place.details', [
            'id' => $placeId
        ]));

    }

    public function edit($req, $res, $args) {
        return $this->view->render($res, 'place-translate/update.twig', [
            'place_translate' => PlaceTranslate::find($args['id']),
            'languages' => Language::where('is_actual', true)->get(),
        ]);
    }
  
    public function update($req, $res) {
        $fullName = trim($req->getParam('full_name')); 
        $languageId = $req->getParam('language_id'); 

        if (!$fullName) {
            $this->flash->addMessage('error', Message::dataEmpty());
            return $res->withRedirect($this->router->pathFor('place.translate.update', [
                'id' => $req->getParam('id')
            ]));  
        }
        
        $placeTranslate = PlaceTranslate::find($req->getParam('id'));
        
        $placeTranslate->update([
            'full_name' => $fullName,
        ]);

        $this->flash->addMessage('success', Message::dataUpdated($shortName));
        
        return $res->withRedirect($this->router->pathFor('place.details', [
            'id' => $placeTranslate->place_id
        ]));
    }

    public function actuality($req, $res) {
        $placeTranslate = PlaceTranslate::find($req->getParam('id'));
        $placeTranslate->update([
            'is_actual' => $placeTranslate->is_actual ? false : true,
        ]);

        $place = Place::find($placeTranslate->place_id);
        return $this->view->render($res, 'place/details.twig', [
            'place' => $place,
            'place_translates' => PlaceTranslate::where('place_id', $place->id)->get()
        ]);
    }

}