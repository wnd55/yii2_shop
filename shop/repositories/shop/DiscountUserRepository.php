<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 12.04.19
 * Time: 12:58
 */

namespace shop\repositories\shop;


use shop\entities\shop\DiscountUser;

class DiscountUserRepository
{


    public function get($id)
    {
        if(!$discountUser = DiscountUser::findOne($id)) {

            throw new \DomainException('Значение не найдено');
        }

        return $discountUser;
    }

    /**
     * @param DiscountUser $discountUser
     */
    public function save(DiscountUser $discountUser) {

        if(!$discountUser->save()) {

            throw new \RuntimeException('Ошибка сохранения');
        }

    }

    /**
     * @param DiscountUser $discountUser
     */
    public function remove(DiscountUser $discountUser) {

        if(!$discountUser->delete()) {

            throw new \RuntimeException('Ошибка удаления');
        }
    }
}