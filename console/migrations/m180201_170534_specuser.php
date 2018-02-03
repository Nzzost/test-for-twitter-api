<?php

use yii\db\Migration;

/**
 * Class m180201_170534_specuser
 */
class m180201_170534_specuser extends Migration
{
    public function up()
    {
        $this->createTable('specuser', [
            'u_id'      => $this->primaryKey(),
            'name'      => $this->string()->notNull(),
            'id'        => $this->string()->notNull(),
            'secret'    => $this->string()->notNull()->unique(),
        ]);
    }

    public function down()
    {
         $this->dropTable('specuser');
    }
}
