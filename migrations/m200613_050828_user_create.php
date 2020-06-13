<?php

use app\migrations\TableNames;
use yii\db\Migration;

/**
 * Class m200613_050828_user_create
 */
class m200613_050828_user_create extends Migration
{

    use TableNames;

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createTable(
            $this->table_users,
            [
                'id'                           => $this->primaryKey(),
                'phone'                        => $this->string(15)->notNull(),
                'password_hash'                => $this->string(),
                'first_name'                   => $this->string()->notNull(),
                'middle_name'                  => $this->string(),
                'last_name'                    => $this->string()->notNull(),
                'status'                       => $this->smallInteger(3),
                'role'                         => $this->smallInteger(3),
                'avatar'                       => $this->string(),
                'house_id'                     => $this->integer(),
                'flat_area'                    => $this->double()->comment(
                    'временно, пока не допилим нормальную интеграцию'
                ),
                'bio'                          => $this->text(),
                /** integrations */ 'esia_uid' => $this->string(),
                'vk_uid'                       => $this->string(),
                'google_uid'                   => $this->string(),
                'facebook_uid'                 => $this->string(),
                'yandex_uid'                   => $this->string(),
                'housing_uid'                  => $this->string(),
            ]
        );

        $this->createTable(
            $this->table_house,
            [
                'id'     => $this->primaryKey(),
                'city'   => $this->string(),
                'street' => $this->string(),
                'house'  => $this->string(),
                'housing_id'=>$this->integer()
            ]
        );

        $this->createTable(
            $this->table_housing,
            [
                'id'         => $this->primaryKey(),
                'name'       => $this->string(),
                'legal_name' => $this->string(),
                'data'       => $this->json(),
            ]
        );

        $this->addForeignKey(
            'fk_user_house',
            $this->table_users,
            'house_id',
            $this->table_house,
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_house_housing',
            $this->table_house,
            'housing_id',
            $this->table_housing,
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->createIndex('idx_uniq_users_phone',$this->table_users,'phone',true);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

//        echo "m200613_050828_user_create cannot be reverted.\n";
//
//        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200613_050828_user_create cannot be reverted.\n";

        return false;
    }
    */
}
