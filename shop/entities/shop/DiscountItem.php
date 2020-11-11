<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 17.04.19
 * Time: 22:16
 */

namespace shop\entities\shop;

use yii\db\ActiveRecord;
/**
 * @property integer $discount_id;
 *   @property integer $product_id;
 */
class DiscountItem extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%shop_discounts_items}}';
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'discount_id' => 'Скидка',
            'product_id' => 'Ароматы',
        ];
    }



    public static function create($discountId, $productId)
    {

        $discountItem = new static();

        $discountItem->discount_id = $discountId;
        $discountItem->product_id = $productId;

        return $discountItem;

    }





}