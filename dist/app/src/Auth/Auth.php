<?php

namespace App\Auth;

use App\Models\User;

class Auth {

    public function attempt($userName, $password) {
        $user = User::where('user_name', $userName)->where('is_actual', true)->first();
        if (!$user) { return false; }
     
        if (password_verify($password, $user->password)) {
            $_SESSION['user'] = $user->id;
            return true;
        }
        
        return false;
    }

    public function check() {
        return isset($_SESSION['user']);
    }
    
    public function user() {
        return User::find($_SESSION['user']);
    }

    public function logout() {
        unset($_SESSION['user']);
    }

}