<?php


namespace app\models\Actions\Security;


use app\models\Entity\User;
use Yii;
use yii\base\Model;

class AuthAction extends Model
{
    public $token = '';

    public $phone;
    public $password;

    public function rules()
    {
        return [
            [['password', 'phone'], 'string'],
            ['phone',function(){
                $clearedPhone = User::phoneUnifier($this->phone);
                if (!ctype_digit($clearedPhone) || mb_strlen($clearedPhone)!==11){
                    $this->addError('phone','Неправильный номер');
                    return;
                }
                $this->phone = $clearedPhone;
            }],
            [['password', 'phone'], 'required']
        ];
    }

    public function attributeLabels()
    {

        return [
          'phone'=>'Номер телефона',
          'password'=>'Пароль'
        ];
    }

    public function login()
    {
        if ($this->validate()) {

            $login = User::find()->orWhere(['phone' => User::phoneUnifier($this->phone)])->one();
            if (!$login || !$login->validatePassword($this->password)) {
                $this->addError('password', 'Неправильный номер или пароль.');
                return false;
            }
            $this->token = $login->generateTokens();
            return true;
        }
        return false;
    }

    public function logout()
    {
        try {
            Yii::$app->user->getIdentity()->dropTokens();
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}