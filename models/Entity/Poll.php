<?php


namespace app\models\Entity;


use yii\db\ActiveRecord;

/**
 * Class Poll
 *
 * @package app\models\Entity
 * @property int    $id        [int(11)]
 * @property int    $feed_id   [int(11)]
 * @property string $questions [json]
 */
class Poll extends ActiveRecord
{

    public static function tableName()
    {

        return 'polls';
    }

    public function rules()
    {

        return [
            ['feed_id', 'integer'],
            ['questions', 'safe'],
        ];
    }

    public function getAnswers(){
        return $this->hasMany(PollAnswers::class,['poll_id'=>'id']);
    }
}