<?php


namespace app\models\Actions\Security;


use app\models\Entity\User;

class RestorePasswordAction extends User
{

    public function rules()
    {

        return [
            ['phone', 'required'],
            [
                'phone',
                function () {

                    $clearedPhone = static::phoneUnifier($this->phone);
                    if (!ctype_digit($clearedPhone) || mb_strlen($clearedPhone) !== 11) {
                        $this->addError('phone', 'Неправильный номер');

                        return;
                    }
                    $this->phone = $clearedPhone;
                },
            ],
            [
                ['phone'],
                'exist',
                'skipOnError'     => false,
                'targetClass'     => User::className(),
                'targetAttribute' => ['phone' => 'phone'],
                'message'         => 'Пользователь не найден',
            ],
        ];
    }

    public function restore()
    {
        //TODO: add sms send
        if ($this->validate()) {
            return true;
        }

        return false;
    }
}