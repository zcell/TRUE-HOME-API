<?php


namespace app\controllers\v1;


use app\models\Entity\CommentDislike;
use app\models\Entity\CommentLike;
use app\models\Entity\Comments;
use app\models\Entity\FeedMessage;
use app\models\Search\Feed\CommentsSearch;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;

class CommentsController extends BaseController
{

    public function behaviors()
    {

        return [
            'authenticator' => [
                'class'  => HttpBearerAuth::class,
                'optional'=>['*']

            ],
            'access'        => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow'   => true,
                        'roles'   => [
                            '?','@'
                        ],
                    ],
                    [
                        'actions'=>['create','like','dislike'],
                        'allow'=>true,
                        'roles'=>['@']
                    ]
                ],
            ],
        ];
    }

    public function actionIndex()
    {

        $search = new CommentsSearch();
        $search->load(\Yii::$app->request->get(), '');
        if (!$search->validate()) {
            return $this->errorResponse($search->errors);
        }

        return $search->search();
    }

    public function actionCreate()
    {
        $body = $this->validateBody();
        $feed = new Comments();
        $feed->load($body,'');
        if (!$feed->validate()){
            return $this->errorResponse($feed->errors);
        }
        if (!$feed->save()){
            return $this->errorResponse($feed->errors);
        }

        return (new CommentsSearch(['id'=>$feed->id]))->search()['items'][0];

    }

    public function actionLike(){
        $body = $this->validateBody();
        $entity = new CommentLike();
        $entity->load($body,'');
        if ($entity->validate() && $entity->save()){
            $search =  (new CommentsSearch(['id'=>$entity->comment_id]))->search();

            return $search['items'][0];
        }
        return $this->errorResponse($entity->errors);
    }

    public function actionDislike(){
        $body = $this->validateBody();
        $entity = new CommentDislike();
        $entity->load($body,'');
        if ($entity->validate() && $entity->save()){
            $search =  (new CommentsSearch(['id'=>$entity->comment_id]))->search();

            return $search['items'][0];
        }
        return $this->errorResponse($entity->errors);
    }

}