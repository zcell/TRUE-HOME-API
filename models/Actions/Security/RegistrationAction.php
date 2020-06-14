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
          [['phone','first_name','last_name','house_id'],'required'],
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
        //** I AM VERY SORRY */
        $password =random_int(100000, 999999);
        $this->password_hash = \Yii::$app->getSecurity()->generatePasswordHash($password);
        $this->status= UserStatusEnum::STATUS_UNCONFIRMED;
        $this->role = UserRoleEnum::ROLE_RESIDENT;
        $save = parent::save($runValidation, $attributeNames);
        if ($save){
            $message = 'Здравствуйте, ваш пароль: '.$password;
            $login=\Yii::$app->params['smslogin'];
            $pass=\Yii::$app->params['smspass'];
            $phone='8'.mb_substr($this->phone,1);

            $ch = curl_init();    // инициализация
            curl_setopt($ch, CURLOPT_URL, 'https://smsc.ru/sys/send.php');
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,"login=$login&psw=$pass&phones=$phone&mes=$message");
            $result = curl_exec($ch);
            curl_close($ch);

             $this->token = $this->generateTokens();
            return true;
        }
        return false;
    }

}