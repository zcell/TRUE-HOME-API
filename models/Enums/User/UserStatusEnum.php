<?php


namespace app\models\Enums\User;


use app\models\Enums\BaseEnum;

class UserStatusEnum extends BaseEnum
{

    const STATUS_UNCONFIRMED = 10;
    const STATUS_CONFIRMED=20;

    public static function getNames(){
        return [
            self::STATUS_UNCONFIRMED=>'Не подтвержден',
            self::STATUS_CONFIRMED=>'Подтвержден',
        ];
    }
}