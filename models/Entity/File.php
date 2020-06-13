<?php


namespace app\models\Entity;


use app\models\Integration\S3;
use yii\db\ActiveRecord;

/**
 * Class File
 *
 * @package app\models\Entity
 * @property int    $id      [int(11)]
 * @property string $name    [varchar(255)]
 * @property int    $size    [int(11)]
 * @property string $type    [varchar(255)]
 * @property string $link    [varchar(255)]
 * @property int    $feed_id [int(11)]
 */
class File extends ActiveRecord
{

    public function rules()
    {

        return [
            [['name','size','type','link'],'required'],
            [['name','type','link'],'string'],
            [['size','feed_id'],'integer'],
            [
                'feed_id',
                'exist',
                'skipOnError'     => true,
                'targetClass'     => FeedMessage::class,
                'targetAttribute' => ['feed_id' => 'id'],
                'message'         => 'Пост не найден',
            ]
        ];
    }

    public static function tableName()
    {

        return '{{%files}}';
    }

    public function getFeedMessage(){
        return $this->hasOne(FeedMessage::class,['id'=>'feed_id']);
    }



    public function afterSave($insert, $changedAttributes)
    {

        parent::afterSave($insert, $changedAttributes);
        if (mb_strpos($this->link, '/s3/temporary/') === 0){
            /** @var S3 $s3 */
            $s3 = \Yii::$app->get('s3');
            $fileName=mb_substr($this->link,14);
            $fromPath= 'temporary/'.$fileName;
            $toPath='posts/'.$this->feed_id.'/'.$fileName;
            $s3->copyFile($fromPath,$toPath);
            $this->link =  '/s3/'.$toPath;
            $this->save();
        }

    }

}