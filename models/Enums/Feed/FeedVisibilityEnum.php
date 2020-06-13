<?php

namespace app\models\Enums\Feed;


class FeedVisibilityEnum extends \app\models\Enums\BaseEnum
{

    const VISIBILITY_ALL      = 10;
    const VISIBILITY_MY_HOUSE = 20;


    public static function getNames()
    {

        return [
            self::VISIBILITY_ALL      => 'Видно всем',
            self::VISIBILITY_MY_HOUSE => 'Только мой дом',
        ];
    }


}