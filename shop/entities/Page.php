<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 14.02.19
 * Time: 11:08
 */

namespace shop\entities;


use paulzi\nestedsets\NestedSetsBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;


/**
 * @property integer $id
 * @property string $slug
 * @property string $title
 * @property string $content
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property Page $parent
 * @property Page[] $parents
 * @property Page[] $children
 * @property Page $prev
 * @property Page $next
 * @mixin NestedSetsBehavior
 */
class Page extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%pages}}';
    }


    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,


        ];
    }

    public function behaviors()
    {
        return [

            NestedSetsBehavior::class,

            'slug' => [
                'class' => SluggableBehavior::class,
                'attribute' => 'title',
                'ensureUnique' => true,
                'immutable' => false

            ]
        ];
    }


    /**
     * @param $title
     * @param $content
     * @param $meta_title
     * @param $meta_description
     * @param $meta_keywords
     * @return static
     */
    public static function create($title, $content, $meta_title, $meta_description, $meta_keywords)
    {
        $page = new static();


        $page->title = $title;
        $page->content = $content;
        $page->meta_title = $meta_title;
        $page->meta_description = $meta_description;
        $page->meta_keywords = $meta_keywords;

        return $page;


    }

    /**
     * @param $title
     * @param $content
     * @param $meta_title
     * @param $meta_description
     * @param $meta_keywords
     * @param $slug
     */
    public function edit($title, $content, $meta_title, $meta_description, $meta_keywords, $slug)
    {

        $this->title = $title;
        $this->content = $content;
        $this->meta_title = $meta_title;
        $this->meta_description = $meta_description;
        $this->meta_keywords = $meta_keywords;
        $this->slug = $slug;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'slug' => 'Slug',
            'content' => 'Текст',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'depth' => 'Depth',
        ];
    }





    /**
     * @return string
     */
    public function getSeoTitle()
    {

        return $this->meta_title ? $this->meta_title : $this->getHeadingTile();


    }

    /**
     * @return string
     */
    public function getHeadingTile()
    {
        return $this->title ? $this->title : $this->name;
    }


}