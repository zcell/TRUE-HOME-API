<?php


namespace app\models\Enums\Feed;


use app\models\Enums\BaseEnum;

class FeedStatusEnum extends BaseEnum
{
    const STATUS_NO_STATUS = 0;
    const STATUS_PLAN =10;
    const STATUS_IN_WORK=20;
    const STATUS_REALISATION = 30;


    public static function getNames()
    {

        return [
            self::STATUS_NO_STATUS=>'',
            self::STATUS_PLAN=>'План работ',
            self::STATUS_IN_WORK=>'В работе',
            self::STATUS_REALISATION=>'Реализация'
        ];
    }

}