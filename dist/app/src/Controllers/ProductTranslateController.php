<?php
    
namespace App\Controllers;

use App\Models\Product;
use App\Models\ProductTranslate;
use App\Models\Language;
use App\Common\Message;

class ProductTranslateController extends Controller {
    
    public function create($req, $res, $args) {
        $product = Product::find($args['id']);
        return $this->view->render($res, 'product-translate/create.twig', [
            'product' => $product,
            'languages' => Language::where('is_actual', true)->get(),
        ]);
    }

    public function store($req, $res) {
        $productId = $req->getParam('product_id');
        $languageId = $req->getParam('language_id');
        $fullName = trim($req->getParam('full_name'));

        if (!$fullName) {
            $this->flash->addMessage('error', Message::dataEmpty());
            return $res->withRedirect($this->router->pathFor('product.translate.create', [
                'id' => $productId
            ]));
        }

        $isExists = ProductTranslate::where('product_id', $productId)
            ->where('language_id', $languageId)->count();
        
        if ($isExists) {
            $this->flash->addMessage('error', Message::dataRefExists());
            return $res->withRedirect($this->router->pathFor('product.translate.create', [
                'id' => $productId
            ]));
        }

        ProductTranslate::create([
            'product_id' => $productId,
            'language_id' => $languageId,
            'full_name' => $fullName,
        ]);
        
        $this->flash->addMessage('success', Message::dataCreated($fullName));
       
        return $res->withRedirect($this->router->pathFor('product.details', [
            'id' => $productId
        ]));

    }

    public function edit($req, $res, $args) {
        return $this->view->render($res, 'product-translate/update.twig', [
            'product_translate' => ProductTranslate::find($args['id']),
            'languages' => Language::where('is_actual', true)->get(),
        ]);
    }
  
    public function update($req, $res) {
        $fullName = trim($req->getParam('full_name')); 
        $languageId = $req->getParam('language_id'); 

        if (!$fullName) {
            $this->flash->addMessage('error', Message::dataEmpty());
            return $res->withRedirect($this->router->pathFor('product.translate.update', [
                'id' => $req->getParam('id')
            ]));  
        }
        
        $productTranslate = ProductTranslate::find($req->getParam('id'));
        $isExists = ProductTranslate::where('language_id', $languageId)
            ->where('id', '<>', $productTranslate->id)->count();
        
        if ($isExists) {
            $this->flash->addMessage('error', Message::dataRefExists());
            return $res->withRedirect($this->router->pathFor('product.translate.update', [
                'id' => $req->getParam('id')
            ]));     
        }

        $productTranslate->update([
            'full_name' => $fullName,
            'language_id' => $languageId 
        ]);

        $this->flash->addMessage('success', Message::dataUpdated($shortName));
        
        return $res->withRedirect($this->router->pathFor('product.details', [
            'id' => $productTranslate->product_id
        ]));
    }

}