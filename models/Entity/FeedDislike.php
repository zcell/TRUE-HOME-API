<?php


namespace app\models\Entity;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class FeedDislike
 *
 * @package app\models\Entity
 * @property int $id         [int(11)]
 * @property int $author_id  [int(11)]
 * @property int $feed_id    [int(11)]
 * @property int $created_at [int(11)]
 * @property int $updated_at [int(11)]
 */
class FeedDislike extends ActiveRecord
{
    public static function tableName()
    {

        return 'feed_dislike';
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
            ['author_id','default','value'=>\Yii::$app->user->id],
            [
                'feed_id',
                'exist',
                'skipOnError'     => false,
                'targetClass'     => FeedMessage::class,
                'targetAttribute' => ['feed_id' => 'id'],
                'message'         => 'Пост не найден',
            ],
            [
                'author_id',
                'exist',
                'skipOnError'     => false,
                'targetClass'     => User::class,
                'targetAttribute' => ['author_id' => 'id'],
                'message'         => 'Пользователь не найден',
            ],
        ];
    }

}