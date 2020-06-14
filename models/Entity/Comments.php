<?php


namespace app\models\Entity;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class Comments
 *
 * @package app\models\Entity
 * @property int    $id         [int(11)]
 * @property int    $feed_id    [int(11)]
 * @property int    $user_id    [int(11)]
 * @property int    $reply_to   [int(11)]
 * @property string $text
 * @property int    $created_at [int(11)]
 * @property int    $updated_at [int(11)]
 *
 * @property-read CommentLike $likes
 * @property-read CommentDislike $dislikes
 */
class Comments extends ActiveRecord
{
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
            [['feed_id','user_id','text'],'required'],
            [['feed_id','user_id','reply_to'],'integer'],
            [
                'feed_id',
                'exist',
                'skipOnError'     => false,
                'targetClass'     => FeedMessage::class,
                'targetAttribute' => ['feed_id' => 'id'],
                'message'         => 'Пост не найден',
            ],
            [
                'user_id',
                'exist',
                'skipOnError'     => false,
                'targetClass'     => User::class,
                'targetAttribute' => ['user_id' => 'id'],
                'message'         => 'Пользователь не найден',
            ],

            ['text','string'],

        ];
    }

    public static function tableName()
    {

        return 'comments';
    }

    public function getUser(){
        return $this->hasOne(User::class,['id'=>'user_id']);
    }

    public function getLikes(){
        return $this->hasMany(CommentLike::class,['comment_id'=>'id']);
    }

    public function getDislikes(){
        return $this->hasMany(CommentDislike::class,['comment_id'=>'id']);
    }
}