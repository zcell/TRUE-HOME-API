<?php

use yii\db\Migration;

/**
 * Class m200613_141135_fix_feed
 */
class m200613_141135_fix_feed extends Migration
{
    use \app\migrations\TableNames;
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn($this->table_feed_message,'public');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200613_141135_fix_feed cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200613_141135_fix_feed cannot be reverted.\n";

        return false;
    }
    */
}
