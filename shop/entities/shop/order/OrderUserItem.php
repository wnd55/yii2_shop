<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 17.02.19
 * Time: 17:45
 */

namespace shop\entities\shop\order;

use shop\entities\shop\product\Product;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property string $product_name
 * @property string $product_code
 * @property string $product_variant
 * @property string $modification_name
 * @property string $modification_code
 * @property int $price
 * @property int $quantity
 */

class OrderUserItem extends ActiveRecord
{




    public static function tableName()
    {
        return '{{%shop_orders_user_items}}';
    }



    public static function createUser(OrderUser $orderUser, Product $product, $price, $quantity, $variant)
    {
        $item = new static;

        $item->order_id = $orderUser->id;
        $item->product_id = $product->id;
        $item->product_name = $product->name;
        $item->product_code = $product->code;
        $item->product_variant = $variant;
        $item->price = $price;
        $item->quantity = $quantity;


        return $item;

    }

}