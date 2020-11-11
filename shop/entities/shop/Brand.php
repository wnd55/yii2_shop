<?php
/**
 * Created by PhpStorm.
 * User: MaximIV
 * Date: 18.03.2018
 * Time: 14:45
 */
namespace shop\entities\shop;

use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;


/**
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $meta_title
 * @property string $meta_description
 * @property  string $meta_keywords
 */

class Brand extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%shop_brands}}';
    }

    public function behaviors()
    {
        return [

            'slug' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'ensureUnique' => true,
                'immutable' => false
            ]

        ];

    }

    /**
     * @param $name
     * @param $meta_title
     * @param $meta_description
     * @param $meta_keywords
     * @return static
     */
        public static function create($name, $meta_title, $meta_description, $meta_keywords )
    {
        $brand = new static();

        $brand->name = $name;
        $brand->meta_title = $meta_title;
        $brand->meta_description = $meta_description;
        $brand->meta_keywords = $meta_keywords;

        return $brand;

    }

    /**
     * @param $name
     * @param $meta_title
     * @param $meta_description
     * @param $meta_keywords
     */
    public function edit($name, $meta_title, $meta_description, $meta_keywords)
    {
        $this->name = $name;
        $this->meta_title = $meta_title;
        $this->meta_description = $meta_description;
        $this->meta_keywords = $meta_keywords;



    }




}