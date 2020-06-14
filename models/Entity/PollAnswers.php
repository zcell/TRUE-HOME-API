<?php


namespace app\models\Entity;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class PollAnswers
 *
 * @package app\models\Entity
 * @property int $id         [int(11)]
 * @property int $poll_id    [int(11)]
 * @property int $user_id    [int(11)]
 * @property int $answer     [int(11)]
 * @property int $created_at [int(11)]
 * @property int $updated_at [int(11)]
 */
class PollAnswers extends ActiveRecord
{

    public function behaviors()
    {

        return [
            [
                'class' => TimestampBehavior::className(),
            ]
        ];
    }

    public function rules()
    {

        return [
          [['poll_id','user_id','answer'],'integer'],
          ['user_id','default','value'=>\Yii::$app->user->id],
          [['user_id','poll_id'],'unique','targetAttribute'=>'answer','message'=>'Вы уже проголосовали']
        ];
    }

    public static function tableName()
    {

        return 'poll_answers';
    }
}