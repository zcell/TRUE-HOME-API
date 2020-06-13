<?php


namespace app\commands;


use app\models\Integration\S3;
use yii\console\Controller;

class TestController extends Controller
{

    public function actionTest()
    {



        /** @var \Aws\S3\S3Client $s3 */
        $s3 = \Yii::$app->get('s3')->client;
        $s3->putObject(
            [
                'Bucket'=>'true-home-public-bucket',
                'Key'  => 'test.md',
                'Body' => file_get_contents('README.md'),
            ]
        );
    }

    public function actionTestmove(){
        /** @var S3 $s3 */
        $s3 = \Yii::$app->get('s3');
        $s3->copyFile('temporary/10248220__image_2020-05-06_18-04-04.png',
                      'test/10248220__image_2020-05-06_18-04-04.png');
    }
}