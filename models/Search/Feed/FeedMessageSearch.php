<?php


namespace app\models\Search\Feed;


use app\models\Entity\FeedMessage;
use app\models\Enums\Feed\FeedTypeEnum;
use app\models\Enums\User\UserRoleEnum;
use yii\db\Query;

class FeedMessageSearch extends FeedMessage
{

    public $limit    = 15;

    public $from_id  = null;

    public $level    = null;

    public $isActual = false;

    public $house_id = null;

    public function rules()
    {

        return [
            [['limit', 'id'], 'integer'],
            ['from_id', 'integer'],
            ['isActual', 'boolean'],
            [['visibility', 'type', 'level', 'house_id','status'], 'integer'],
            ['title', 'string'],
        ];
    }


    public function search()
    {

        //** sorry me, i don't have enough time  */
        $query = static::find();
        $query->with(
            ['files', 'linked', 'author', 'poll', 'poll.answers', 'linked.author', 'likes', 'dislikes', 'comments']
        );
        $query->orderBy('created_at desc');
        $query->andFilterWhere(
            [

                'type' => $this->type,
                'id'   => $this->id,
                'status'=>$this->status
            ]
        );
        if (!empty($this->visibility)) {
            $query->andWhere(['visibility' => $this->visibility]);
        }
        if (!empty($this->level)) {
            $query->andWhere(['visibility' => $this->level]);
        }


        if (!\Yii::$app->user->isGuest && \Yii::$app->user->identity->role == UserRoleEnum::ROLE_HOUSING_WORKER) {
        } else {
            $query->andWhere(
                [
                    '!=',
                    'type',
                    FeedTypeEnum::TYPE_PRIVATE_APPEAL,
                ]
            );
        }
        if (!empty($this->title)) {
            $query->andWhere(['like', 'title', $this->title]);
        }


        if ($this->isActual) {
            $query->from(
                (new Query)->select(['*', "`commcnt` + `likescnt` + `dislikescnt` as rates"])->from(
                    (new Query())->select(
                        [
                            '`feed_message`.*',
                            'comm.`cnt`*3      as commcnt',
                            'likes.`cnt`*2    as likescnt',
                            'dislikes.`cnt` as dislikescnt',
                        ]
                    )->from('feed_message')->leftJoin(
                        '(select feed_id, count(1) as cnt from comments group by feed_id) comm',
                        'feed_message.id=comm.feed_id'
                    )->leftJoin(
                        '(select feed_id, count(1) as cnt from feed_like group by feed_id) likes',
                        'feed_message.id = likes.feed_id'
                    )->leftJoin(
                        '(select feed_id, count(1) as cnt from feed_dislike group by feed_id) dislikes',
                        'feed_message.id = dislikes.feed_id'
                    )->where(['>', 'created_at', 'UNIX_TIMESTAMP()-2592000'])
                )
            )->orderBy('rates desc');
        }

        if ($this->from_id !== null) {
            $query->where(['<', 'id', $this->from_id]);
        }


        if (\Yii::$app->user->identity !== null) {
            $user_id = \Yii::$app->user->identity->id;
        } else {
            $user_id = null;
        }
        if (!empty($this->house_id) && $this->house_id!=1) {
            $query->andWhere(['id'=>'title']);
        }

        $payload = array_map(
            function ($item) use ($user_id) {

                $polls = $item['poll'];

                if (count($polls)) {
                    $item['poll_id'] = $item['poll'][0]['id'];
                    $item['poll'] = $this->formatePoll($polls[0]);
                }
                $this->formateUser($item['author']);

                foreach ($item['linked'] as &$linked) {
                    $this->formateUser($linked['author']);
                }

                $Liked = array_filter(
                    $item['likes'],
                    function ($i) use ($user_id) {

                        return $i['author_id'] == $user_id;
                    }
                );
                $Disliked = array_filter(
                    $item['dislikes'],
                    function ($i) use ($user_id) {

                        return $i['author_id'] == $user_id;
                    }
                );
                $item['likes'] = count($item['likes']);
                $item['dislikes'] = count($item['dislikes']);
                $item['comments'] = count($item['comments']);

                $item['isLiked'] = (bool)count($Liked);
                $item['isDisliked'] = (bool)count($Disliked);


                //                $item['comments'] = 123;
                //                $item['likes'] = 123;
                //                $item['dislikes'] = 123;
                //                $item['isLiked'] = true;
                //                $item['isDisliked'] = false;

                return $item;
            },
            $query->limit($this->limit)->asArray(true)->all()
        );

        return $payload;
    }


    private function formatePoll($poll)
    {

        $questions = json_decode($poll['questions']);
        $formattedPoll = [];
        $counter = 0;
        foreach ($questions as $quest) {
            $formattedPoll[] = [
                'question' => $quest,
                'answers'  => 0,
                'id'       => $counter++,
            ];
        }

        foreach ($poll['answers'] as $answer) {
            $formattedPoll[(int)$answer['answer']]['answers'] += 1;
        }

        return $formattedPoll;
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