<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 19.02.18
 * Time: 17:37
 */

namespace shop\entities\blog;


use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $title
 * @property string $description
 * @property int $sort
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keyword
 */
class Category extends ActiveRecord
{


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


    public static function create($name, $title, $description, $sort, $meta_title, $meta_description, $meta_keyword)
    {

        $category = new static();

        $category->name = $name;
        $category->title = $title;
        $category->description = $description;
        $category->sort = $sort;
        $category->meta_title = $meta_title;
        $category->meta_description = $meta_description;
        $category->meta_keyword = $meta_keyword;

        return $category;

    }


    public function edit($name, $title, $description, $sort, $meta_title, $meta_description, $meta_keyword)
    {

        $this->name = $name;
        $this->title = $title;
        $this->description = $description;
        $this->sort = $sort;
        $this->meta_title = $meta_title;
        $this->meta_description = $meta_description;
        $this->meta_keyword = $meta_keyword;

    }


    public static function tableName()
    {
        return '{{%blog_categories}}';
    }

    public function getHeadingTile()
    {
        return $this->title ?: $this->name;
    }


}

