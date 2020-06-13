<?php

use yii\db\Migration;

/**
 * Class m200613_175925_poll_creates
 */
class m200613_175925_poll_creates extends Migration
{
    use \app\migrations\TableNames;
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table_poll,[
            'id'=>$this->primaryKey(),
            'feed_id'=>$this->integer(),
            'questions'=>$this->json()
        ]);


        $this->createTable($this->table_poll_answers,[
            'id'=>$this->primaryKey(),
            'poll_id'=>$this->integer(),
            'user_id'=>$this->integer(),
            'answer'=>$this->integer(),
            'created_at'=>$this->integer(),
            'updated_at'=>$this->integer()
        ]);

        $this->addForeignKey('fk_poll_answers_poll',$this->table_poll_answers,'poll_id',$this->table_poll,'id','CASCADE','CASCADE');
        $this->addForeignKey('fk_poll_answers_user',$this->table_poll_answers,'user_id',$this->table_users,'id','CASCADE','CASCADE');
        $this->addForeignKey('fk_poll_feed',$this->table_poll,'feed_id',$this->table_feed_message,'id','CASCADE','CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200613_175925_poll_creates cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200613_175925_poll_creates cannot be reverted.\n";

        return false;
    }
    */
}
