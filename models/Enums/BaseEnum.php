<?php


namespace app\models\Enums;


class BaseEnum
{

    public static function getNames(){
        return [];
    }

    public static function getName($key){
        $enums = static::getNames();
        if (array_key_exists($key,$enums)){
            return $enums[$key];
        }
        return 'Не найдено';
    }
}