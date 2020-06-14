<?php

use yii\db\Migration;

/**
 * Class m200614_065227_status_poll
 */
class m200614_065227_status_poll extends Migration
{

    use \app\migrations\TableNames;
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table_feed_message,'status',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200614_065227_status_poll cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200614_065227_status_poll cannot be reverted.\n";

        return false;
    }
    */
}
