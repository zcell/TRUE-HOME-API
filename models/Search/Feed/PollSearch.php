<?php


namespace app\models\Search\Feed;


use app\models\Entity\Poll;

class PollSearch extends Poll
{
    public function rules()
    {

        return [
            ['id','integer']
        ];
    }

    public function search(){
        $query = Poll::find()->with('answers')->where(['id'=>$this->id]);
        $data = $query->asArray()->all();
        if (count($data)){
            $data = $this->formatePoll($data[0]);
        }

        return $data;

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
                'id'=>$counter++
            ];
        }

        foreach ($poll['answers'] as $answer) {
            $formattedPoll[(int)$answer['answer']]['answers'] += 1;
        }

        return $formattedPoll;
    }
}