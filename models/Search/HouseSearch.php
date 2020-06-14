<?php


namespace app\models\Search;


use app\models\Entity\House;

class HouseSearch extends House
{

    public $name;

    public function rules()
    {

        return [
            ['id', 'integer'],
            ['name', 'string'],
        ];
    }

    public function search()
    {

        $query = static::find();


        if (!empty($this->name)) {
            $splits = explode(' ', $this->name);
            foreach ($splits as $split) {
                $query->orWhere(
                    [
                        'or',
                        ['like', 'street', $this->name],
                        ['like', 'house', $this->name],

                    ]
                );
            }
        }

        return $query->asArray()->all();
    }
}