<?php

use yii\db\Migration;

/**
 * Class m200613_131503_feed_message_create
 */
class m200613_131503_feed_message_create extends Migration
{

    use \app\migrations\TableNames;
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table_feed_message,[
            'id'=>$this->primaryKey(),
            'author_id'=>$this->integer(),
            'title'=>$this->string(),
            'text'=>'longtext',
            'due_date'=>$this->integer(11),
            'start_date'=>$this->integer(),
            'created_at'=>$this->integer(),
            'updated_at'=>$this->integer()
        ]);

        $this->addForeignKey('fk_feed_msg_user',$this->table_feed_message,'author_id',$this->table_users,'id','CASCADE','CASCADE');

        $this->createTable($this->table_feed_links,[
           'id'=>$this->primaryKey(),
           'feed_id'=>$this->integer(),
           'link_id'=>$this->integer()
        ]);

        $this->addForeignKey('fk_feed_links_feed',$this->table_feed_links,'feed_id',$this->table_feed_message,'id','CASCADE','CASCADE');
        $this->addForeignKey('fk_feed_links_link',$this->table_feed_links,'link_id',$this->table_feed_message,'id','CASCADE','CASCADE');

        $this->createTable($this->table_files,[
           'id'=>$this->primaryKey(),
           'name'=>$this->string(),
           'size'=>$this->integer(),
           'type'=>$this->string(),
           'link'=>$this->string(),
           'feed_id'=>$this->integer()
        ]);

        $this->addForeignKey('fk_files_feed',$this->table_files,'feed_id',$this->table_feed_message,'id','SET NULL','CASCADE');

        $this->createTable($this->feed_like,[
           'id'=>$this->primaryKey(),
           'author_id'=>$this->integer(),
           'feed_id'=>$this->integer(),
           'created_at'=>$this->integer(),
           'updated_at'=>$this->integer()
        ]);

        $this->addForeignKey('fk_feed_likes_feed',$this->feed_like,'feed_id',$this->table_feed_message,'id','CASCADE','CASCADE');
        $this->addForeignKey('fk_feed_likes_author',$this->feed_like,'author_id',$this->table_users,'id','CASCADE','CASCADE');

        $this->createTable($this->feed_dislike,[
            'id'=>$this->primaryKey(),
            'author_id'=>$this->integer(),
            'feed_id'=>$this->integer(),
            'created_at'=>$this->integer(),
            'updated_at'=>$this->integer()
        ]);

        $this->addForeignKey('fk_feed_dislike_feed',$this->feed_dislike,'feed_id',$this->table_feed_message,'id','CASCADE','CASCADE');
        $this->addForeignKey('fk_feed_dislike_author',$this->feed_dislike,'author_id',$this->table_users,'id','CASCADE','CASCADE');




    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200613_131503_feed_message_create cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200613_131503_feed_message_create cannot be reverted.\n";

        return false;
    }
    */
}
