<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 13.04.19
 * Time: 13:44
 */

namespace shop\entities\shop;


use yii\db\ActiveRecord;

/**
 * @property integer $discount_user_id
 * @property integer $user_id
 */
class DiscountUserItem extends ActiveRecord
{


    public static function tableName()
    {
        return '{{%shop_discounts_users_items}}';
    }


    public static function create($discountUserId, $userId)
    {

        $item = new static();
        $item->discount_user_id = $discountUserId;
        $item->user_id = $userId;

        return $item;

    }



    public function getDiscount()
    {

        return $this->hasOne(DiscountUser::class, ['id' => 'discount_user_id']);

    }


}