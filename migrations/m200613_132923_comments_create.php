<?php

use yii\db\Migration;

/**
 * Class m200613_132923_comments_create
 */
class m200613_132923_comments_create extends Migration
{

    use \app\migrations\TableNames;
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->table_comments,[
           'id'=>$this->primaryKey(),
           'feed_id'=>$this->integer(),
           'user_id'=>$this->integer(),
           'reply_to'=>$this->integer(),
           'text'=>'longtext',
           'created_at'=>$this->integer(),
           'updated_at'=>$this->integer()
        ]);

        $this->createTable($this->table_comments_like,[
           'id'=>$this->primaryKey(),
           'author_id'=>$this->integer(),
           'comment_id'=>$this->integer(),
           'created_at'=>$this->integer(),
           'updated_at'=>$this->integer()
        ]);

        $this->createTable($this->table_comments_dislike,[
            'id'=>$this->primaryKey(),
            'author_id'=>$this->integer(),
            'comment_id'=>$this->integer(),
            'created_at'=>$this->integer(),
            'updated_at'=>$this->integer()
        ]);

        $this->addForeignKey('fk_comments_feed',$this->table_comments,'feed_id',$this->table_feed_message,'id','CASCADE','CASCADE');
        $this->addForeignKey('fk_comments_user',$this->table_comments,'user_id',$this->table_users,'id','CASCADE','CASCADE');
        $this->addForeignKey('fk_comments_reply',$this->table_comments,'reply_to',$this->table_users,'id','CASCADE','CASCADE');

        $this->addForeignKey('fk_comment_like_author',$this->table_comments_like,'author_id',$this->table_users,'id','CASCADE','CASCADE');
        $this->addForeignKey('fk_comment_like_comment',$this->table_comments_like,'comment_id',$this->table_comments,'id','CASCADE','CASCADE');

        $this->addForeignKey('fk_comment_dislike_author',$this->table_comments_dislike,'author_id',$this->table_users,'id','CASCADE','CASCADE');
        $this->addForeignKey('fk_comment_dislike_comment',$this->table_comments_dislike,'comment_id',$this->table_comments,'id','CASCADE','CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200613_132923_comments_create cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200613_132923_comments_create cannot be reverted.\n";

        return false;
    }
    */
}
