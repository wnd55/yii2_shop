<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 31.01.19
 * Time: 12:24
 */

namespace shop\entities\user;

use yii\db\ActiveRecord;

/**
 * @property integer $user_id
 * @property integer $product_id
 */

class WishlistItem extends ActiveRecord
{




    public static function tableName()
    {
        return '{{%user_wishlist_items}}';
    }




    public static function create($userId, $productId)
    {
        $item = new static();

        $item ->user_id = $userId;
        $item->product_id = $productId;

        return $item;

    }

    public function isForProduct($productId)
    {
        return $this->product_id == $productId;
    }




}