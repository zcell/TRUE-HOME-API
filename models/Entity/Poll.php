<?php


namespace app\models\Entity;


use yii\db\ActiveRecord;

class Poll extends ActiveRecord
{
        public static function tableName()
        {

            return 'poll';
        }
}