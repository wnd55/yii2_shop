<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 13.04.19
 * Time: 14:49
 */

namespace shop\repositories\shop;


use shop\entities\shop\DiscountUserItem;

class DiscountUserItemRepository
{

    public function get($id)
    {

        if (!$discountUserItem = DiscountUserItem::findOne($id)) {

            throw new \DomainException('Значение не найдено');
        }

        return $discountUserItem;
    }

    public function save(DiscountUserItem $discountUserItem)
    {

        if (!$discountUserItem->save()) {

            throw new \RuntimeException('Ошибка сохранения');
        }
    }


    public function remove(DiscountUserItem $discountUserItem)
    {

        if (!$discountUserItem->delete()) {

            throw new \RuntimeException('Ошибка удаления');
        }
    }


    public function removeUserDiscount($userId)
    {

        $userDiscount = DiscountUserItem::find()->where(['user_id' => $userId])->one();

        if (!$userDiscount->delete()) {

            throw new \RuntimeException('Ошибка удаления');
        }


    }

    /**
     * @param $id
     * @return bool
     */
    public function existsByDiscountUserItem($id)
    {

        return DiscountUserItem::find()->where(['discount_user_id' => $id])->exists();


    }


    /**
     * @param $userId
     * @return bool
     */
    public function existsUserDiscountUserItem($userId)
    {


        return DiscountUserItem::find()->where(['user_id' => $userId])->exists();


    }
}