<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 05.06.18
 * Time: 14:14
 */

namespace shop\helpers;


use shop\entities\shop\order\OrderGuest;
use shop\entities\shop\order\OrderUser;
use shop\entities\shop\product\Product;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class OrderHelper
{
    /**
     * @return array
     */

    public static function statusUserList()
    {

        return [

            OrderUser::NEW_ORDER => 'Новый заказ клиента',
            OrderUser::PAID => 'Paid',
            OrderUser::SENT => 'Sent',
            OrderUser::COMPLETED => 'Completed',
            OrderUser::CANCELLED => 'Cancelled',
            OrderUser::CANCELLED_BY_CUSTOMER => 'Cancelled by customer',


        ];


    }

    /**
     * @param $status
     * @return mixed
     */

    public static function statusUserName($status)
    {

        return ArrayHelper::getValue(self::statusUserList(), $status);


    }


    /**
     * @param $status
     * @return string
     */

    public static function statusUserLabel($status)
    {

        switch ($status) {

            case OrderUser::NEW_ORDER:
                $class = 'label label-success';
                break;

            case OrderUser::PAID:
                $class = 'label label-success';
                break;

            case OrderUser::CANCELLED_BY_CUSTOMER:
                $class = 'label label-warning';
                break;

            default:

                $class = 'label label-default';

        }

        return Html::tag('span', ArrayHelper::getValue(self::statusUserList(), $status), ['class' => $class]);

    }


    /**
     * @return array
     */

    public static function statusGuestList()
    {

        return [

            OrderGuest::NEW_ORDER => 'Новый заказ гостя',
            OrderGuest::PAID => 'Paid',
            OrderGuest::SENT => 'Sent',
            OrderGuest::COMPLETED => 'Completed',
            OrderGuest::CANCELLED => 'Cancelled',
            OrderGuest::CANCELLED_BY_CUSTOMER => 'Cancelled by customer',


        ];


    }

    /**
     * @param $status
     * @return mixed
     */

    public static function statusGuestName($status)
    {

        return ArrayHelper::getValue(self::statusGuestList(), $status);


    }


    /**
     * @param $status
     * @return string
     */

    public static function statusGuestLabel($status)
    {

        switch ($status) {

            case OrderGuest::NEW_ORDER:
                $class = 'label label-success';
                break;

            case OrderGuest::PAID:
                $class = 'label label-success';
                break;

            case OrderGuest::CANCELLED_BY_CUSTOMER:
                $class = 'label label-warning';
                break;

            default:

                $class = 'label label-default';

        }

        return Html::tag('span', ArrayHelper::getValue(self::statusGuestList(), $status), ['class' => $class]);

    }


}