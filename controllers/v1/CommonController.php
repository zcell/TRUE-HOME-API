<?php


namespace app\controllers\v1;


use app\models\Integration\S3;
use app\models\Search\HouseSearch;
use app\models\Search\UserSearch;
use yii\web\UploadedFile;

class CommonController extends BaseController
{

    public function actionUpload()
    {

        $file = UploadedFile::getInstanceByName('file');

        if ($file !== null) {
            $filename = mt_rand() . '__' . $file->name;
            /** @var S3 $s3 */
            $s3 = \Yii::$app->get('s3');
            [$path,$fileMime,$fileSize] = $s3->uploadFile($file->tempName, $filename);

            return [
                'fileName' => $file->name,
                'filePath' => '/s3/' . $path,
                'fileType'=>$fileMime,
                'fileSize'=>$fileSize
            ];
        }

        \Yii::$app->response->statusCode = 422;

        return 'error on save';
    }

    public function actionHouses(){
        $entity = new HouseSearch();
        $entity->load(\Yii::$app->request->get(),'');
        return $entity->search();
    }

    public function actionUsers(){
        $search = new UserSearch();
        $search->load(\Yii::$app->request->get(),'');
        return $search->search();
    }
}