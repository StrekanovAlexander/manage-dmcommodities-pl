<?php
    
namespace App\Controllers;

use App\Models\Language;
use App\Common\Message;

class LanguageController extends Controller {
    
    public function index($req, $res) {
        return $this->view->render($res, 'language/index.twig', [
           'languages' => Language::orderBy('id')->get() 
        ]);
    }

    public function create($req, $res) {
        return $this->view->render($res, 'language/create.twig');
    }

    public function store($req, $res) {
        $shortName = trim($req->getParam('short_name'));

        if (!$shortName) {
            $this->flash->addMessage('error', Message::dataEmpty());
            return $res->withRedirect($this->router->pathFor('language.create'));
        }

        $shortName = strtolower($shortName);
       
        $isExists = Language::where('short_name', $shortName)->count();
        
        if ($isExists) {
            $this->flash->addMessage('error', Message::dataExists($shortName));
            return $res->withRedirect($this->router->pathFor('language.create'));
        }

        Language::create([
            'short_name' => $shortName,
        ]);
        
        $this->flash->addMessage('success', Message::dataCreated($shortName));
       
        return $res->withRedirect($this->router->pathFor('language.details', [
            'id' => Language::max('id')
        ]));

    }

    public function details($req, $res, $args) {
        $language = Language::find($args['id']);
        return $this->view->render($res, 'language/details.twig', [
            'language' => $language,
        ]);
    }

    public function edit($req, $res, $args) {
        $language = Language::find($args['id']);
        return $this->view->render($res, 'language/update.twig', [
            'language' => $language,
        ]);
    }
  
    public function update($req, $res) {
        $shortName = trim($req->getParam('short_name')); 
        
        if (!$shortName) {
            $this->flash->addMessage('error', Message::dataEmpty());
            return $res->withRedirect($this->router->pathFor('language.update', [
                'id' => $req->getParam('id')
            ]));   
        }

        $shortName = strtolower($shortName);
        
        $language = Language::find($req->getParam('id'));
        $isExists = Language::where('short_name', $shortName)->where('id', '<>', $language->id)->count();
        
        if ($isExists) {
            $this->flash->addMessage('error', Message::dataExists($shortName));
            return $res->withRedirect($this->router->pathFor('language.update', [
                'id' => $req->getParam('id')
            ]));       
        }

        $language->update([
            'short_name' => $shortName,
            'is_actual' => $req->getParam('is_actual') ? true : false,
        ]);

        $this->flash->addMessage('success', Message::dataUpdated($shortName));
        
        return $res->withRedirect($this->router->pathFor('language.details', [
            'id' => $req->getParam('id')
        ]));
    }

    public function actuality($req, $res) {
        $language = Language::find($req->getParam('id'));
        $language->update([
            'is_actual' => $language->is_actual ? false : true,
        ]);

        $this->flash->addMessage('success', Message::dataUpdated($language->short_name));
        
        return $res->withRedirect($this->router->pathFor('language.index'));
    }
 
}