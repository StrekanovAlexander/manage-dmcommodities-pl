<?php

namespace App\Common;

class JSON {

    public static function body($res, $arr) {
        return $res->getBody()->write(json_encode($arr, JSON_UNESCAPED_UNICODE));
    }

    public static function header($res) {
        return  $res->withHeader('Content-type', 'application/json; charset=utf-8')
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    }

}