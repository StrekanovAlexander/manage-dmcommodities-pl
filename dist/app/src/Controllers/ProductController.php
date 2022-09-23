<?php
    
namespace App\Controllers;

use App\Models\Product;
use App\Models\ProductTranslate;
use App\Common\Message;

class ProductController extends Controller {
    
    public function index($req, $res) {
        return $this->view->render($res, 'product/index.twig', [
           'products' => Product::orderBy('full_name')->get() 
        ]);
    }

    public function create($req, $res) {
        return $this->view->render($res, 'product/create.twig');
    }

    public function store($req, $res) {
        $fullName = trim($req->getParam('full_name'));

        if (!$fullName) {
            $this->flash->addMessage('error', Message::dataEmpty());
            return $res->withRedirect($this->router->pathFor('product.create'));
        }

        $isExists = Product::where('full_name', $fullName)->count();
        
        if ($isExists) {
            $this->flash->addMessage('error', Message::dataExists($fullName));
            return $res->withRedirect($this->router->pathFor('product.create'));
        }

        Product::create([
            'full_name' => $fullName,
        ]);
        
        $this->flash->addMessage('success', Message::dataCreated($fullName));
       
        return $res->withRedirect($this->router->pathFor('product.details', [
            'id' => Product::max('id')
        ]));

    }

    public function details($req, $res, $args) {
        $product = Product::find($args['id']);
        return $this->view->render($res, 'product/details.twig', [
            'product' => $product,
            'product_translates' => ProductTranslate::where('product_id', $product->id)->get()
        ]);
    }

    public function edit($req, $res, $args) {
        return $this->view->render($res, 'product/update.twig', [
            'product' => Product::find($args['id']),
        ]);
    }
  
    public function update($req, $res) {
        $fullName = trim($req->getParam('full_name')); 

        if (!$fullName) {
            $this->flash->addMessage('error', Message::dataEmpty());
            return $res->withRedirect($this->router->pathFor('product.update', [
                'id' => $req->getParam('id')
            ]));  
        }
        
        $product = Product::find($req->getParam('id'));
         
        $isExists = Product::where('full_name', $fullName)->where('id', '<>', $product->id)->count();
        
        if ($isExists) {
            $this->flash->addMessage('error', Message::dataExists($fullName));
            return $res->withRedirect($this->router->pathFor('product.update', [
                'id' => $req->getParam('id')
            ]));       
        }

        $product->update([
            'full_name' => $fullName,
            'is_actual' => $req->getParam('is_actual') ? true : false,
        ]);

        $this->flash->addMessage('success', Message::dataUpdated($fullName));
        
        return $res->withRedirect($this->router->pathFor('product.details', [
            'id' => $req->getParam('id')
        ]));
    }

    public function actuality($req, $res) {
        $product = Product::find($req->getParam('id'));
        $product->update([
            'is_actual' => $product->is_actual ? false : true,
        ]);

        return $res->withRedirect($this->router->pathFor('product.index'));
    }

}