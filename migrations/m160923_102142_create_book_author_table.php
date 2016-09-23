<?php

use yii\db\Migration;

/**
 * Handles the creation for table `book_author`.
 */
class m160923_102142_create_book_author_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('book_author', [
            'book_id' => $this->integer(),
            'author_id' => $this->integer(),
        ]);

        $this->addForeignKey('book_author_book', 'book_author', 'book_id', 'book', 'id');
        $this->addForeignKey('book_author_author', 'book_author', 'author_id', 'author', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('book_author_book', 'book_author');
        $this->dropForeignKey('book_author_author', 'book_author');
        $this->dropTable('book_author');
    }
}
