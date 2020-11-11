<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 19.03.18
 * Time: 13:29
 */

namespace shop\entities\shop;


use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string $name
 * @property string $slug
 */
class Tag extends ActiveRecord
{


    public static function tableName()
    {
        return '{{%shop_tags}}';
    }


    public function behaviors()
    {
        return [

            'slug' => [
                'class' => SluggableBehavior::class,
                'attribute' => 'name',
                'ensureUnique' => true,
                'immutable' => false

            ]

        ];
    }

    public static function create($name)
    {
        $tag = new static();
        $tag->name = $name;

        return $tag;

    }

    public function edit($name)
    {
        $this->name = $name;



    }

}