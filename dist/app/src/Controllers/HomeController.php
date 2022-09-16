<?php
    
namespace App\Controllers;

class HomeController extends Controller {
    
    public function index($req, $res) {
        return $this->view->render($res, 'home/index.twig');
    }

}
