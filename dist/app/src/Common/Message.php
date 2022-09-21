<?php

namespace App\Common;

class Message {

    const MESSAGES = [
        'DATA_CREATED' => 'Запис було створено: ',
        'DATA_EXISTS' => 'Відмова! Запис вже існує: ',
        'DATA_EMPTY' => 'Відмова! Порожній рядок',
        'DATA_INCORRECT' => 'Відмова! Некоректні дані: ',
        'DATA_REF_EXISTS' => 'Відмова! Некоректні зв`язки',
        'DATA_UPDATED' => 'Запис було відредаговано: ',
        'PWD_CHANGED' => 'Гасло було змінено',
        'PWD_NOT_MATCH' => 'Відмова! Гасла не співпадають',
        'PWD_INCORRECT' => 'Відмова! Некоректні дані гасла',
    ];

    public static function dataCreated($data) {
        return self::MESSAGES['DATA_CREATED'] . $data;
    }
    
    public static function dataExists($data) {
        return self::MESSAGES['DATA_EXISTS'] . $data;
    }

    public static function dataEmpty() {
        return self::MESSAGES['DATA_EMPTY'];
    }

    public static function dataIncorrect($data) {
        return self::MESSAGES['DATA_INCORRECT'] . $data;
    }

    public static function dataRefExists() {
        return self::MESSAGES['DATA_REF_EXISTS'];
    }

    public static function dataUpdated($data) {
        return self::MESSAGES['DATA_UPDATED'] . $data;
    }

    public static function pwdChanged() {
        return self::MESSAGES['PWD_CHANGED'];
    }

    public static function pwdNotMatch() {
        return self::MESSAGES['PWD_NOT_MATCH'];
    }

    public static function pwdIncorrect() {
        return self::MESSAGES['PWD_INCORRECT'];
    }
    
}

