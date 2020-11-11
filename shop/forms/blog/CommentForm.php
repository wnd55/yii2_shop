<?php

namespace shop\forms\blog;

use yii\base\Model;

/**
 * Class CommentForm
 * @package shop\forms\blog
 */
class CommentForm extends Model
{
    public $parentId;
    public $text;

    public function rules()
    {
        return [
            [['text'], 'required'],
            ['text', 'string'],
            ['parentId', 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'post_id' => 'Post ID',
            'user_id' => 'User ID',
            'parent_id' => 'Parent ID',
            'created_at' => 'Created At',
            'text' => 'Комментарий',
            'active' => 'Active',
        ];
    }

}