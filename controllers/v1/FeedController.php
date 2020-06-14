<?php


namespace app\controllers\v1;


use app\models\Entity\FeedDislike;
use app\models\Entity\FeedLike;
use app\models\Entity\FeedMessage;
use app\models\Entity\PollAnswers;
use app\models\Search\Feed\FeedMessageSearch;
use app\models\Search\Feed\PollSearch;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;

class FeedController extends BaseController
{

    public function behaviors()
    {

        return [
            'authenticator' => [
                'class' => HttpBearerAuth::class,
                'optional'=>['*']
            ],
            'access'        => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                      'actions'=>['index'],
                      'allow'=>true,
                      'roles'=>['@','?']
                    ],
                    [
                        'actions' => ['actual', 'create', 'like', 'dislike', 'vote'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionCreate()
    {

        $body = $this->validateBody();
        $feed = new FeedMessage();
        $feed->load($body, '');
        if (!$feed->validate()) {
            return $this->errorResponse($feed->errors);
        }
        if (!$feed->save()) {
            return $this->errorResponse($feed->errors);
        }

        return [];
    }

    public function actionLike()
    {

        $body = $this->validateBody();
        $entity = new FeedLike();
        $entity->load($body, '');
        if ($entity->validate() && $entity->save()) {
            $search = (new FeedMessageSearch(['id' => $entity->feed_id]))->search();

            return $search[0];
        }

        return $this->errorResponse($entity->errors);
    }

    public function actionDislike()
    {

        $body = $this->validateBody();
        $entity = new FeedDislike();
        $entity->load($body, '');
        if ($entity->validate() && $entity->save()) {
            $search = (new FeedMessageSearch(['id' => $entity->feed_id]))->search();

            return $search[0];
        }

        return $this->errorResponse($entity->errors);
    }

    public function actionIndex()
    {

        $search = new FeedMessageSearch();
        $search->load(\Yii::$app->request->get(), '');
        if (!$search->validate()) {
            return $this->errorResponse($search->errors);
        }

        return $search->search();
    }

    public function actionActual()
    {

        $search = new FeedMessageSearch();
        $search->load(\Yii::$app->request->get(), '');
        $search->isActual = true;
        if (!$search->validate()) {
            return $this->errorResponse($search->errors);
        }

        return $search->search();
    }

    public function actionVote()
    {

        $body = $this->validateBody();
        $vote = new PollAnswers();
        $vote->load($body, '');
        if ($vote->validate() && $vote->save()) {
            return (new PollSearch(['id' => $vote->poll_id]))->search();
        }

        return $this->errorResponse($vote->errors);
    }
}