<?php


namespace app\controllers\v1;


use app\models\Entity\FeedLike;
use app\models\Entity\FeedMessage;
use app\models\Search\Feed\FeedMessageSearch;
use yii\filters\auth\HttpBearerAuth;

class FeedController extends BaseController
{
    public function behaviors()
    {

        return [
            'authenticator' => [
                'class' => HttpBearerAuth::class,
                'except' => ['index']
            ]
        ];
    }

    public function actionCreate(){
        $body = $this->validateBody();
        $feed = new FeedMessage();
        $feed->load($body,'');
        if (!$feed->validate()){
            return $this->errorResponse($feed->errors);
        }
        if (!$feed->save()){
            return $this->errorResponse($feed->errors);
        }
        return [];
    }

    public function actionLike(){
        $body = $this->validateBody();
        $entity = new FeedLike();
        $entity->load($body,'');
        if ($entity->validate() && $entity->save()){
            return '';
        }
        return $this->errorResponse($entity->errors);
    }

    public function actionDislike(){
        $body = $this->validateBody();
        $entity = new Feed();
        $entity->load($body,'');
        if ($entity->validate() && $entity->save()){
            return '';
        }
        return $this->errorResponse($entity->errors);
    }

    public function actionIndex(){
        $search = new FeedMessageSearch();
        $search->load(\Yii::$app->request->get(), '');
        if (!$search->validate()) {
            return $this->errorResponse($search->errors);
        }
        return $search->search();
    }
}