<?php
    
namespace App\Controllers;

use App\Models\User;
use App\Common\Message;

class UserController extends Controller {
    const MIN_LEN = 5;
    
    public function index($req, $res) {
        return $this->view->render($res, 'user/index.twig', [
           'users' => User::orderBy('user_name')->get() 
        ]);
    }

    public function create($req, $res) {
        return $this->view->render($res, 'user/create.twig', [
            'conditions' => $this->conditions()
        ]);
    }

    public function store($req, $res) {
        $userName = trim($req->getParam('username'));

        if (!$userName) {
            $this->flash->addMessage('error', Message::dataEmpty());
            return $res->withRedirect($this->router->pathFor('user.create'));
        }
        
        if (!$this->validate($userName)) {
            $this->flash->addMessage('error', Message::dataIncorrect($userName));
            return $res->withRedirect($this->router->pathFor('user.create'));
        }

        if ($this->isExists($userName)) {
            $this->flash->addMessage('error', Message::dataExists($userName));
            return $res->withRedirect($this->router->pathFor('user.create'));
        }

        User::create([
            'user_name' => $userName,
            'password' => bin2hex(random_bytes(10)),
        ]);
        
        $this->flash->addMessage('success', Message::dataCreated($userName));

        return $res->withRedirect($this->router->pathFor('user.details', [
            'id' => User::max('id')
        ]));
    }

    public function details($req, $res, $args) {
        $user = User::find($args['id']);
        return $this->view->render($res, 'user/details.twig', [
            'user' => $user,
        ]);
    }

    public function edit($req, $res, $args) {
        $user = User::find($args['id']);
        return $this->view->render($res, 'user/update.twig', [
            'user' => $user,
            'conditions' => $this->conditions()
        ]);
    }

    public function update($req, $res) {
        $user = User::find($req->getParam('id'));
        $userName = trim($req->getParam('username')); 

        if (!$this->validate($userName)) {
            $this->flash->addMessage('error', Message::dataIncorrect($userName));
            return $res->withRedirect($this->router->pathFor('user.update', [
                'id' => $req->getParam('id')
            ]));  
        }
        
        $isExists = User::where('user_name', $userName)->where('id', '<>', $user->id)->count();
        
        if ($isExists) {
            $this->flash->addMessage('error', Message::dataExists($userName));
            return $res->withRedirect($this->router->pathFor('user.update', [
                'id' => $req->getParam('id')
            ]));       
        }

        $user->update([
            'user_name' => $userName,
            'is_actual' => $req->getParam('is_actual') ? true : false,
        ]);

        $this->flash->addMessage('success', Message::dataUpdated($userName));
        
        return $res->withRedirect($this->router->pathFor('user.details', [
            'id' => $req->getParam('id')
        ]));
    }

    public function editPassword($req, $res, $args) {
        $user = User::find($args['id']);
        return $this->view->render($res, 'user/update-password.twig', [
            'user' => $user,
            'conditions' => $this->conditions()
        ]);
    }

    public function updatePassword($req, $res) {

        $user = User::find($req->getParam('id'));
        $password = $req->getParam('password');
        $password2 = $req->getParam('password2');

        if (!$this->validate($password)) {
            $this->flash->addMessage('error', Message::pwdIncorrect());
            return $res->withRedirect($this->router->pathFor('user.update.password', [
                'id' => $req->getParam('id')
            ]));
        }
        
        if (!$this->compare($password, $password2)) {
            $this->flash->addMessage('error', Message::pwdNotMatch());
            return $res->withRedirect($this->router->pathFor('user.update.password', [
                'id' => $req->getParam('id')
            ]));
        }

        $user->update([
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
        
        $this->flash->addMessage('success', Message::pwdChanged());

        return $res->withRedirect($this->router->pathFor('user.details', [
            'id' => $req->getParam('id')
        ]));
        
    }

    public function actuality($req, $res) {
        $user = User::find($req->getParam('id'));
        $user->update([
            'is_actual' => $user->is_actual ? false : true,
        ]);

        return $res->withRedirect($this->router->pathFor('user.index'));
    }

    public function getLogin($req, $res) {
        return $this->view->render($res, 'user/login.twig');
    }

    public function postLogin($req, $res) {
        $result = $this->auth->attempt($req->getParam('username'), $req->getParam('password'));
        
        if (!$result) {
            return $res->withRedirect($this->router->pathFor('user.login'));
        }

        return $res->withRedirect($this->router->pathFor('home.index'));
    }

    public function logout($req, $res) {
        $this->auth->logout();
        return $res->withRedirect($this->router->pathFor('user.login'));
    }

    private function validate($str) {
        $pattern = '/[A-Za-z0-9]{' . self::MIN_LEN . ',}/';
        return preg_match($pattern, $str);
    }

    private function isExists($userName) {
        return User::where('user_name', $userName)->count() > 0;
    }

    private function compare($password, $password2) {
        return $password == $password2;
    }

    private function conditions() {
        return [ 
            'Символи: A-Z a-z 0-9',
            'Довжина: від ' . self::MIN_LEN . ' символів' 
        ];    
    } 

}