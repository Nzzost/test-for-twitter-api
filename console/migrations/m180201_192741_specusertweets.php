<?php

use yii\db\Migration;

/**
 * Class m180201_192741_specusertweets
 */
class m180201_192741_specusertweets extends Migration
{
    public function up()
    {
        $this->createTable('specusertweets', [
            'id'            => $this->primaryKey(),
            'specuser_id'   => $this->integer()->notNull(),
            'tweet_id'      => $this->string()->notNull(),
            'tweet'         => $this->text()->notNull(),
            'hashtags'      => $this->text(),
        ]);

        $this->createIndex(
            'idx-specuser_id',
            'specusertweets',
            'specuser_id'
        );

        $this->addForeignKey(
            'fk-specuser_id',
            'specusertweets',
            'specuser_id',
            'specuser',
            'u_id',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'fk-specuser_id',
            'specusertweets'
        );

        $this->dropIndex(
            'idx-specuser_id',
            'specusertweets'
        );

        $this->dropTable('specusertweets');
    }
}
