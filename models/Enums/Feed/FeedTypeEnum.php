<?php


namespace app\models\Enums\Feed;


use app\models\Enums\BaseEnum;

class FeedTypeEnum extends BaseEnum
{

    const TYPE_VOTING =10;
    const TYPE_MEETING=20;
    const TYPE_DISCUSSION = 30;
    const TYPE_PRIVATE_APPEAL = 40;

    public static function getNames()
    {

        return [
            self::TYPE_DISCUSSION=>'Публичное обращение',
            self::TYPE_MEETING=>'Собрание',
            self::TYPE_VOTING=>'Голосование',
            self::TYPE_PRIVATE_APPEAL=>'Приватное обращение'
        ];
    }
}