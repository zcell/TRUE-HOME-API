<?php


namespace app\controllers\v1;


use Yii;
use yii\base\InvalidArgumentException;
use yii\helpers\Json;
use yii\rest\Controller;

class BaseController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {

        return parent::behaviors();
    }


    protected function validateBody()
    {

        $body = Yii::$app->request->rawBody;
        if (!empty($body)) {
            try {
                $body = Json::decode($body);
            } catch (\yii\base\InvalidArgumentException $e) {
                throw new InvalidArgumentException("invalid body");
            }
        } else {
            throw new InvalidArgumentException("Empty body");
        }

        return $body;
    }

    protected function errorResponse($data)
    {

        \Yii::$app->response->statusCode = 422;

        return [
            'errors' => $data,
        ];
    }

    protected function notFoundResponse($name)
    {

        \Yii::$app->response->statusCode = 404;

        return [
            'errors' => "$name не найден(а)",
        ];
    }
}