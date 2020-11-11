<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 17.02.19
 * Time: 16:44
 */

namespace shop\repositories\shop;

use shop\entities\shop\order\OrderUser;
use yii\web\NotFoundHttpException;

class OrderUserRepository
{
    /**
     * @param $id
     * @return null|static
     * @throws NotFoundHttpException
     */

    public function get($id)
    {
        if (!$order = OrderUser::findOne($id)) {
            throw new NotFoundHttpException('OrderUser is not found.');
        }
        return $order;
    }

    /**
     * @param OrderUser $order
     * @return OrderUser
     */
    public function save(OrderUser $order)
    {
        if (!$order->save() ) {
            throw new \RuntimeException('Saving error.');

        }

        else return $order;
    }

    /**
     * @param OrderUser $order
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */

    public function remove(OrderUser $order)
    {
        if (!$order->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }












}