<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 18.04.19
 * Time: 10:55
 */

namespace shop\repositories\shop;


use shop\entities\shop\Discount;

class DiscountRepository
{

    /**
     * @param $id
     * @return null|static
     */
    public function get($id)
    {

        if(!$discount = Discount::findOne($id)) {

            throw new \DomainException('Скидка не найдена.');
        }

        return $discount;
    }

    /**
     * @param Discount $discount
     */
    public function save(Discount $discount)
    {
        if(!$discount->save()) {

            throw new \RuntimeException('Скидка не найдена.');
        }



    }

    /**
     * @param Discount $discount
     */
    public function remove(Discount $discount) {


        if(!$discount->delete()) {

            throw new \RuntimeException('Ошибка удаления');
        }

    }

}