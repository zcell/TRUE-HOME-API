<?php


namespace app\models\Search\Feed;


use app\models\Entity\FeedMessage;
use yii\log\EmailTarget;

class FeedMessageSearch extends FeedMessage
{

    public $limit = 10;
    public $from_id = null;

    public function rules()
    {

        return [
            ['limit','integer'],
            ['from_id','integer']
        ];
    }


    public function search(){
        $query = static::find();
        $query->with(['files','linked']);
        $query->orderBy('created_at desc');
        if ($this->from_id!==null){
            $query->where(['<','id',$this->from_id]);
        }


        return $query->limit($this->limit)->asArray(true)->all();
    }
}