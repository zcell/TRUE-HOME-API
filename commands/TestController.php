<?php


namespace app\commands;


use app\models\Integration\S3;
use yii\console\Controller;

class TestController extends Controller
{

    public function actionTest()
    {
        $message = 'Здравствуйте, ваш пароль: '.'213ads';
        $message = urlencode($message);
        $login=\Yii::$app->params['smslogin'];
        $pass=\Yii::$app->params['smspass'];
        $phone='8'.mb_substr('79011094788',1);

    }

    public function actionTestmove(){
        /** @var S3 $s3 */
        $s3 = \Yii::$app->get('s3');
        $s3->copyFile('temporary/10248220__image_2020-05-06_18-04-04.png',
                      'test/10248220__image_2020-05-06_18-04-04.png');
    }
}