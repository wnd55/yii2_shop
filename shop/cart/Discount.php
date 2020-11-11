<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 19.04.19
 * Time: 23:15
 */

namespace shop\cart;

use shop\entities\shop\DiscountUser;
use shop\entities\shop\DiscountUserItem;
use shop\entities\user\User;
use shop\repositories\UserRepository;
use Yii;

class Discount
{


    public $cart;


    public function __construct(Cart $cart)
    {

        $this->cart = $cart;
    }

    /**
     * @param $user
     * @return bool
     */
    public function isUserHasActiveDiscount($user)
    {


        return $user->userDiscountUserItem && $user->userDiscountUser->status == true;

    }


    /**
     * @param $user
     * @param $totalCountWithBonus
     * @return float
     */
    public function userDiscount($user, $totalCountWithBonus)
    {

        if ($this->isUserHasActiveDiscount($user)) {


            $discountUserValue = $user->userDiscountUser->percent;

            $temp = round($totalCountWithBonus - ($totalCountWithBonus * $discountUserValue / 100));

            return $temp;
        }

        return $totalCountWithBonus;

    }






}