<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use \yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "book".
 *
 * @property integer $id
 * @property string $name
 * @property string $created_at
 * @property string $published
 *
 * @property Author[] $authors
 */
class Book extends ActiveRecord
{
    public $authors;

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'book';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['published', 'authors'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'created_at' => 'Created At',
            'published' => 'Published',
            'authors' => 'Authors',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthors()
    {
        return $this->hasMany(Author::className(), ['id' => 'author_id'])
            ->viaTable('book_author', ['book_id' => 'id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $command = Yii::$app->db->createCommand();
        $command->delete('book_author', [
            'book_id' => $this->id,
        ])->execute();
        if ($this->authors) {
            foreach ($this->authors as $author) {
                $exists = (new Query())
                    ->from(Author::tableName())
                    ->where([
                        'id' => $author,
                    ])
                    ->exists();
                if ($exists) {
                    $command->insert('book_author', [
                        'book_id' => $this->id,
                        'author_id' => $author,
                    ])->execute();
                }
            }
        }
        return parent::afterSave($insert, $changedAttributes);
    }

    public function afterFind()
    {
        $this->authors = (new Query())
            ->select('author_id')
            ->from('book_author')
            ->where([
                'book_id' => $this->id,
            ])
            ->column();
        parent::afterFind();
    }

    /**
     * @return string
     */
    public function getAuthorList()
    {
        return implode(', ', ArrayHelper::map($this->getAuthors()->asArray()->all(), 'id', 'name'));
    }
}
