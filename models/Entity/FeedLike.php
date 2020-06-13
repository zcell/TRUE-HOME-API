<?php


namespace app\models\Entity;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class FeedLike
 *
 * @package app\models\Entity
 * @property int $id         [int(11)]
 * @property int $author_id  [int(11)]
 * @property int $feed_id    [int(11)]
 * @property int $created_at [int(11)]
 * @property int $updated_at [int(11)]
 */
class FeedLike extends ActiveRecord
{

    public static function tableName()
    {

        return '{{%feed_like}}';
    }

    public function behaviors()
    {

        return [
            [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }

    public function rules()
    {

        return [
            [['author_id', 'feed_id'],'integer'],
            [
                'feed_id',
                'exist',
                'skipOnError'     => false,
                'targetClass'     => FeedMessage::class,
                'targetAttribute' => ['id' => 'feed_id'],
                'message'         => 'Пост не найден',
            ],
            [
                'author_id',
                'exist',
                'skipOnError'     => false,
                'targetClass'     => User::class,
                'targetAttribute' => ['id' => 'author_id'],
                'message'         => 'Пользователь не найден',
            ],
        ];
    }

}