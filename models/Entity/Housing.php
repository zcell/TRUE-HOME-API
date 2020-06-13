<?php


namespace app\models\Entity;


use yii\db\ActiveRecord;

/**
 * Class Housing
 *
 * @package app\models\Entity
 * @property int    $id         [int(11)]
 * @property string $name       [varchar(255)]
 * @property string $legal_name [varchar(255)]
 * @property string $data       [json]
 */
class Housing extends ActiveRecord
{

    public function rules()
    {

        return [
            [['name','legal_name'],'string'],
            [['name','legal_name'],'required']
        ];
    }

    public function afterFind()
    {
        parent::afterFind();
        if (is_string($this->data)){
            $this->data = json_decode($this->data);
        }
    }

    public function beforeValidate()
    {
        if (is_string($this->data)){
            $this->data = json_decode($this->data);
        }
        return parent::beforeValidate();
    }

    public static function tableName()
    {

        return '{{%housing}}';
    }

}