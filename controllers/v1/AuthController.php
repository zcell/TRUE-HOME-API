<?php


namespace app\controllers\v1;


use app\models\Actions\Security\AuthAction;
use app\models\Actions\Security\RegistrationAction;
use app\models\Actions\Security\RestorePasswordAction;
use app\models\Entity\User;
use app\models\Enums\User\UserRoleEnum;
use app\models\Enums\User\UserStatusEnum;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;

class AuthController extends BaseController
{

    public function behaviors()
    {

        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'authenticator' => [
                    'class'  => HttpBearerAuth::className(),
                    'except' => ['login', 'register', 'restore-password'],
                ],
            ]
        );
    }

    public function actionRegister()
    {

        $body = $this->validateBody();
        $action = new RegistrationAction();
        if ($action->load($body, '') && $action->save()) {
            $action->generateTokens();

            return [
                'token' => $action->token,
            ];
        }

        return $this->errorResponse($action->errors);
    }


    public function actionRestorePassword()
    {

        $body = $this->validateBody();
        $action = new RestorePasswordAction();
        if ($action->load($body, '') && $action->restore()) {
            return true;
        }

        return $this->errorResponse($action->errors);
    }


    public function actionLogin()
    {

        $body = $this->validateBody();
        $action = new AuthAction();
        if ($action->load($body, '') && $action->login()) {
            return [
                'token' => $action->token,
            ];
        }

        return $this->errorResponse($action->errors);
    }

    public function actionMe()
    {

        /** @var User $me */
        $me = \Yii::$app->user->identity;

        return [
            'success' => true,
            'code'    => 200,
            'data'    => [
                'id'          => $me->id,
                'accessToken' => User::getCurrentToken(),
                'first_name'  => $me->first_name,
                'middle_name' => $me->middle_name,
                'last_name'   => $me->last_name,
                'role'        => UserRoleEnum::getName($me->role),
                'status'      => UserStatusEnum::getName($me->status),
                'avatar'      => (empty($me->avatar)) ? '' : \yii\helpers\Url::to($me->avatar),
                'isResident'  => $me->role === UserRoleEnum::ROLE_RESIDENT,
                'isHousing'   => $me->role === UserRoleEnum::ROLE_HOUSING_WORKER,
            ],
        ];
    }

    public function actionLogout()
    {

        $action = new AuthAction();
        $result = $action->logout();
        if (!$result) {
            return $this->errorResponse('cant logout');
        }

        return [];
    }
}