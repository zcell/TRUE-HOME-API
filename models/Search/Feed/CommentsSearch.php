<?php


namespace app\models\Search\Feed;


use app\models\Entity\Comments;
use Yii;

class CommentsSearch extends Comments
{

    public $limit = 10;

    public $page  = 1;

    public function rules()
    {

        return [
            [['limit', 'page', 'id'], 'integer'],
            ['feed_id', 'integer'],
        ];
    }


    public function search()
    {
    //** sorry me, i don't have enough time  */
        $query = static::find();
        $query->with(['user','likes','dislikes']);
        $query->andFilterWhere(
            [
                'id' => $this->id,
                'feed_id' => $this->feed_id
            ]
        );
        $query->orderBy('created_at desc');
        $count = $query->count();
        $meta = [
            'total'      => (int)$count,
            'totalPages' => (int)ceil($count / $this->limit),
            'page'       => (int)$this->page,
            'pageSize'   => (int)$this->limit,
        ];

        if (\Yii::$app->user->identity!==null){
             $user_id = \Yii::$app->user->identity->id;
        }else{
            $user_id = null;
        }
        $query->offset(($this->page - 1) * $this->limit)->limit($this->limit);
        $payload = array_map(
            function ($v) use ($user_id){

                $this->formateUser($v['user']);
                $Liked = array_filter($v['likes'],function ($i) use ($user_id){
                    return $i['author_id']== $user_id;
                });
                $Disliked = array_filter($v['dislikes'],function ($i) use ($user_id){
                    return $i['author_id']== $user_id;
                });
                $v['likes'] = count($v['likes']);
                $v['dislikes'] = count($v['dislikes']);

                $v['isLiked'] = (bool) count($Liked);
                $v['isDisliked'] = (bool) count($Disliked) ;

                return $v;
            },
            $query->asArray()->all()
        );


        return [
            'items' => $payload,
            'meta'  => $meta,
        ];
    }

    private function formateUser(&$user)
    {

        unset($user['password_hash']);
        unset($user['status']);
        unset($user['role']);
        unset($user['esia_uid']);
        unset($user['vk_uid']);
        unset($user['google_uid']);
        unset($user['facebook_uid']);
        unset($user['yandex_uid']);
        unset($user['housing_uid']);
    }
}