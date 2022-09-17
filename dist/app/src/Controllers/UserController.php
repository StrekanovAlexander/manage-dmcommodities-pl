<?php
    
namespace App\Controllers;

use App\Models\User;

class UserController extends Controller {
    const MIN_LEN_USER_DATA = 5;
    const MESSAGES = [
        'DATA_INCORRECT' => 'Відмова! Некоректні дані',
        'USER_EXISTS' => 'Відмова! Користувач існує: ',
        'PWDS_DONT_MATCH' => 'Відмова! Гасла не співпадають',
        'USER_CREATED' => 'Користувача було створено: ',
        'USER_UPDATED' => 'Користувача було відредаговано: ',
        'PWD_CHANGED' => 'Гасло було змінено для користувача: ',
    ];
    
    public function index($req, $res) {
        return $this->view->render($res, 'user/index.twig', [
           'users' => User::orderBy('user_name')->get() 
        ]);
    }

    public function create($req, $res) {
        return $this->view->render($res, 'user/create.twig');
    }

    public function store($req, $res) {
        $userName = trim($req->getParam('username'));
        
        if (!$this->validate($userName, self::MIN_LEN_USER_DATA)) {
            $this->flash->addMessage('error', self::MESSAGES['DATA_INCORRECT']);
            return $res->withRedirect($this->router->pathFor('user.create'));
        }

        if ($this->isExists($userName)) {
            $this->flash->addMessage('error', self::MESSAGES['USER_EXISTS'] . $userName);
            return $res->withRedirect($this->router->pathFor('user.create'));
        }

        User::create([
            'user_name' => $userName,
            'password' => bin2hex(random_bytes(10)),
        ]);
        
        $this->flash->addMessage('success', self::MESSAGES['USER_CREATED'] . $userName);

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
        ]);
    }

    public function update($req, $res) {
        $user = User::find($req->getParam('id'));
        $userName = trim($req->getParam('username')); 

        if (!$this->validate($userName, self::MIN_LEN_USER_DATA)) {
            $this->flash->addMessage('error', self::MESSAGES['DATA_INCORRECT']);
            return $res->withRedirect($this->router->pathFor('user.update', [
                'id' => $req->getParam('id')
            ]));  
        }
        
        $isExists = User::where('user_name', $userName)->where('id', '<>', $user->id)->count();
        
        if ($isExists) {
            $this->flash->addMessage('error', self::MESSAGES['USER_EXISTS'] . $userName);
            return $res->withRedirect($this->router->pathFor('user.update', [
                'id' => $req->getParam('id')
            ]));       
        }

        $user->update([
            'user_name' => $userName,
            'is_actual' => $req->getParam('is_actual') ? true : false,
        ]);

        $this->flash->addMessage('success', self::MESSAGES['USER_UPDATED'] . $userName);
        
        return $res->withRedirect($this->router->pathFor('user.details', [
            'id' => $req->getParam('id')
        ]));
    }

    public function editPassword($req, $res, $args) {
        $user = User::find($args['id']);
        return $this->view->render($res, 'user/update-password.twig', [
            'user' => $user,
        ]);
    }

    public function updatePassword($req, $res) {

        $user = User::find($req->getParam('id'));
        $password = $req->getParam('password');
        $password2 = $req->getParam('password2');

        if (!$this->validate($password, self::MIN_LEN_USER_DATA)) {
            $this->flash->addMessage('error', self::MESSAGES['DATA_INCORRECT']);
            return $res->withRedirect($this->router->pathFor('user.update.password', [
                'id' => $req->getParam('id')
            ]));
        }
        
        if (!$this->compare($password, $password2)) {
            $this->flash->addMessage('error', self::MESSAGES['PWDS_DONT_MATCH']);
            return $res->withRedirect($this->router->pathFor('user.update.password', [
                'id' => $req->getParam('id')
            ]));
        }

        $user->update([
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
        
        $this->flash->addMessage('success', self::MESSAGES['PWD_CHANGED'] . $user->user_name);

        return $res->withRedirect($this->router->pathFor('user.details', [
            'id' => $req->getParam('id')
        ]));
        
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

    private function validate($str, $len) {
        $pattern = '/[A-Za-z0-9]{' . $len . ',}/';
        return preg_match($pattern, $str);
    }

    private function isExists($userName) {
        return User::where('user_name', $userName)->count() > 0;
    }

    private function compare($password, $password2) {
        return $password == $password2;
    }

}