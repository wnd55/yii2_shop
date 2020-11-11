<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 18.01.19
 * Time: 13:32
 */
namespace shop\entities\shop\product;

use yii\db\ActiveRecord;

/**
 * @property integer $product_id;
 * @property integer $category_id;
 */
class CategoryAssignment extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%shop_category_assignments}}';
    }

    public static function create($productId, $categoryId)
    {
        $object = new static();

        $object->product_id = $productId;
        $object->category_id = $categoryId;

        return $object;

    }

    public function isForCategory($id)
    {
        return $this->category_id == $id;
    }


}