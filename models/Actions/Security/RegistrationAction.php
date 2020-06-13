<?php


namespace app\models\Actions\Security;


use app\models\Entity\User;
use app\models\Enums\User\UserRoleEnum;
use app\models\Enums\User\UserStatusEnum;

class RegistrationAction extends User
{

   public $token;

    public function rules()
    {

        return [
          [['phone','first_name','last_name'],'required'],
          ['phone',function(){
              $clearedPhone = static::phoneUnifier($this->phone);
              if (!ctype_digit($clearedPhone) || mb_strlen($clearedPhone)!==11){
                  $this->addError('phone','Неправильный номер');
                  return;
              }
              $this->phone = $clearedPhone;
          }],
          ['phone','unique'],
        ];
    }

    public function attributeLabels()
    {

        return [
          'first_name'=>'Имя',
          'last_name'=>'Фамилия',
          'phone'=>"Номер телефона"
        ];
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        $this->password_hash = \Yii::$app->getSecurity()->generatePasswordHash('123456');
        $this->status= UserStatusEnum::STATUS_UNCONFIRMED;
        $this->role = UserRoleEnum::ROLE_RESIDENT;
        $save = parent::save($runValidation, $attributeNames);
        if ($save){
             $this->token = $this->generateTokens();
            return true;
        }
        return false;
    }

}