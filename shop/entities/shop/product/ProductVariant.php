<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 04.04.19
 * Time: 23:21
 */

namespace shop\entities\shop\product;

use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string $name
 * @property string $size
 * @property integer $price_new
 * @property integer $price_old
 */
class ProductVariant extends ActiveRecord
{


    public static function tableName()
    {
        return '{{%products_variants}}';
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'size' => 'Размер атомайзера',
            'price_new' => 'Новая цена',
            'price_old' => 'Старая цена',
        ];
    }


    /**
     * @param $name
     * @param $size
     * @param $price_new
     * @param $price_old
     * @return static
     */
    public static function create($name, $size, $price_new, $price_old)
    {

        $productVariant = new static();

        $productVariant->name = $name;
        $productVariant->size = $size;
        $productVariant->price_new = $price_new;
        $productVariant->price_old = $price_old;

        return $productVariant;


    }


    public function edit($name, $size, $price_new, $price_old)
    {
        $this->name = $name;
        $this->size = $size;
        $this->price_new = $price_new;
        $this->price_old = $price_old;


    }


}