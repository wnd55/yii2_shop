<?php

namespace shop\forms\manage\blog;

use shop\entities\blog\Tag;
use yii\base\Model;

class TagForm extends Model
{
    public $name;
    // public $slug;

    private $_tag;

    public function __construct(Tag $tag = null, $config = [])
    {
        if ($tag) {
            $this->name = $tag->name;
            $this->_tag = $tag;
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name',], 'string', 'max' => 255],

            [['name'], 'unique', 'targetClass' => Tag::className(), 'filter' => $this->_tag ? ['<>', 'id', $this->_tag->id] : null]
        ];
    }
}