<?php

use yii\db\Migration;

/**
 * Class m200613_134438_feed_fixes
 */
class m200613_134438_feed_fixes extends Migration
{

    use \app\migrations\TableNames;
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn($this->table_feed_message,'visibility',$this->smallInteger(3)->notNull());
        $this->addColumn($this->table_feed_message,'public',$this->smallInteger(3)->notNull());
        $this->addColumn($this->table_feed_message,'type',$this->smallInteger(3)->notNull());


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200613_134438_feed_fixes cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200613_134438_feed_fixes cannot be reverted.\n";

        return false;
    }
    */
}
