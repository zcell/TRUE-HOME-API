<?php


namespace app\commands;


use app\models\Enums\User\UserRoleEnum;
use app\models\Enums\User\UserStatusEnum;
use app\models\Entity\User;
use yii\console\Controller;

class UserController extends Controller
{

    public function actionExist($phone)
    {

        $user = User::findOne(['phone' => $phone]);
        if ($user === null) {
            echo "Пользователь не найден" . PHP_EOL;

            return 0;
        }
        echo PHP_EOL;
        echo "Телефон: " . $user->phone . PHP_EOL;
        echo "ФИО: " . $user->first_name . " " . $user->middle_name . " " . $user->last_name . PHP_EOL;
        echo "Статус: " . UserStatusEnum::getName($user->status) . PHP_EOL;
        echo "Роль: " . UserRoleEnum::getName($user->role) . PHP_EOL;

        return 0;
    }

    public function actionCreate($phone, $password, $firstName, $lastName)
    {

        $user = new User();
        $user->role = UserRoleEnum::ROLE_RESIDENT;
        $user->phone = $phone;
        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->password_hash = \Yii::$app->getSecurity()->generatePasswordHash($password);
        $user->status = UserStatusEnum::STATUS_CONFIRMED;
        if ($user->save()) {
            echo "Пользователь создан" . PHP_EOL;
        } else {
            \Yii::error(
                [
                    'type'  => 'error on create user',
                    $user->errors,
                    'input' => [
                        $phone,
                        $password,
                    ],
                ]
            );
            echo "Ошибка" . PHP_EOL;
        }

        return 0;
    }

    public function actionDelete($phone)
    {
        $user = User::findOne(['phone'=>User::phoneUnifier($phone)]);
        if ($user===null){
            echo "Пользователь не найден" . PHP_EOL;
        }
        $user->delete();
    }
}