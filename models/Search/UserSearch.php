<?php


namespace app\models\Search;


use app\models\Entity\User;

class UserSearch extends User
{

    public function rules()
    {

        return [
            ['status', 'integer'],
        ];
    }

    public function search(){
        $query = User::find();
        $query->with('house');
        $query->andFilterWhere([
            'status'=>$this->status
                         ]);

        return $query->asArray()->all();
    }

}