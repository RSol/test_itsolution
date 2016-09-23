<?php

use yii\db\Migration;

/**
 * Handles the creation for table `book`.
 */
class m160923_102008_create_book_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('book', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'created_at' => $this->dateTime(),
            'published' => $this->date(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('book');
    }
}
