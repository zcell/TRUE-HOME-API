<?php


namespace app\models\Entity;


use yii\db\ActiveRecord;

/**
 *
 * @property int $id      [int(11)]
 * @property int $feed_id [int(11)]
 * @property int $link_id [int(11)]
 */
class FeedLinks extends ActiveRecord
{
    public function rules()
    {

        return [
          [['feed_id','link_id'],'integer'],
          [
              'feed_id',
              'exist',
              'skipOnError'     => true,
              'targetClass'     => FeedMessage::class,
              'targetAttribute' => ['id' => 'feed_id'],
              'message'         => 'Пост не найден',
          ],
          [
                'link_id',
                'exist',
                'skipOnError'     => false,
                'targetClass'     => FeedMessage::class,
                'targetAttribute' => ['link_id' => 'id'],
                'message'         => 'Пост не найден',
            ]
        ];
    }

    public static function tableName()
    {

        return '{{%feed_links}}';
    }

}