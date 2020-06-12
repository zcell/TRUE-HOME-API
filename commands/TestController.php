<?php


namespace app\commands;


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
}