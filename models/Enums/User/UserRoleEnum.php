<?php
namespace app\models\Enums\User;

use app\models\Enums\BaseEnum;

class UserRoleEnum extends BaseEnum
{
    const ROLE_RESIDENT = 10;
    const ROLE_HOUSING_WORKER = 30;

    public static function getNames(){
        return [
            self::ROLE_RESIDENT=>'Житель',
            self::ROLE_HOUSING_WORKER=>'Работник УК'
        ];
    }
}