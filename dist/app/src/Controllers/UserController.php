<?php
    
namespace App\Controllers;

use App\Models\User;

class UserController extends Controller {
    
    public function index($req, $res) {
        return $this->view->render($res, 'user/index.twig', [
           'users' => User::orderBy('user_name')->get() 
        ]);
    }

    public function create($req, $res) {
        return $this->view->render($res, 'user/create.twig');
    }

    public function store($req, $res) {
        return $this->view->render($res, 'user/details.twig');
    }

    public function details($req, $res, $args) {
        $user = User::find($args['id']);
        return $this->view->render($res, 'user/details.twig', [
            'user' => $user,
        ]);
    }

    public function getLogin($req, $res) {
        return $this->view->render($res, 'user/login.twig');
    }

    public function postLogin($req, $res) {
        $result = $this->auth->attempt($req->getParam('username'), $req->getParam('password'));

        $this->flash->addMessage('success', 'Home page');
        
        if (!$result) {
            return $res->withRedirect($this->router->pathFor('user.login'));
        }

        return $res->withRedirect($this->router->pathFor('home.index'));
    }

    public function logout($req, $res) {
        $this->auth->logout();
        return $res->withRedirect($this->router->pathFor('user.login'));
    }

}