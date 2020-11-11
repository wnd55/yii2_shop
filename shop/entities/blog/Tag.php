<?php

namespace shop\entities\blog;

use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string $name
 * @property string $slug
 */
class Tag extends ActiveRecord
{


    /**
     * @return array
     */
    public function behaviors()
    {
        return [

            'slug' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'ensureUnique' => true,
                'immutable' => true
            ]

        ];

    }

    /**
     * @param $name
     * @return static
     */
    public static function create($name)
    {
        $tag = new static();
        $tag->name = $name;

        return $tag;
    }
    /**
     * @param $name
     */
    public function edit($name)
    {
        $this->name = $name;

    }

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%blog_tags}}';
    }
}