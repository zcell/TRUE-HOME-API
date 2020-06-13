<?php


namespace app\models\Entity;


use yii\db\ActiveRecord;

/**
 * Class House
 *
 * @package app\models\Entity
 * @property int          $id         [int(11)]
 * @property string       $city       [varchar(255)]
 * @property string       $street     [varchar(255)]
 * @property string       $house      [varchar(255)]
 * @property int          $housing_id [int(11)]
 *
 * @property-read Housing $housing
 * @property-read User[]  $residents
 */
class House extends ActiveRecord
{

    public static function tableName()
    {

        return '{{%house}}';
    }

    public function rules()
    {

        return [
          [['house','street','city'],'string'],
          [['house','street','city'],'required'],
          ['housing_id','integer'],
          [
              ['housing_id'],
              'exist',
              'skipOnError'     => false,
              'targetClass'     => Housing::class,
              'targetAttribute' => ['id' => 'housing_id'],
              'message'         => 'УК не найдена',
          ],
        ];
    }

    public function getHousing(){
        return $this->hasOne(Housing::class,['id'=>'housing_id']);
    }

    public function getResidents(){
        return $this->hasMany(User::class,['house_id','id']);
    }
}