<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 11.02.19
 * Time: 12:40
 */

namespace shop\entities\shop\order;


use shop\entities\shop\product\Product;
use yii\db\ActiveRecord;


/**
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property int $modification_id
 * @property string $product_name
 * @property string $product_code
 * @property string $product_variant
 * @property string $modification_name
 * @property string $modification_code
 * @property int $price
 * @property int $quantity
 * @property OrderGuest $orderGuest
 * @property Product $product
 */


class OrderGuestItem extends ActiveRecord

{


    public static function tableName()
    {
        return '{{%shop_orders_guest_items}}';
    }

    /**
     * @param OrderGuest $orderGuest
     * @param Product $product
     * @param $price
     * @param $quantity
     * @param $dimensionName
     * @return static
     */
    public static function create(OrderGuest $orderGuest, Product $product, $price, $quantity, $dimensionName)
    {
        $item = new static;

        $item->order_id = $orderGuest->id;
        $item->product_id = $product->id;
        $item->product_name = $product->name;
        $item->product_code = $product->code;
        $item->product_variant = $dimensionName;
        $item->price = $price;
        $item->quantity = $quantity;

        return $item;

    }











}