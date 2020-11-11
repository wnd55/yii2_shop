<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 06.06.18
 * Time: 16:54
 */

namespace shop\services\manage\shop;


use shop\forms\manage\shop\order\OrderEditGuestForm;

use shop\repositories\shop\DeliveryRepository;
use shop\repositories\shop\OrderGuestRepository;

class OrderGuestManageService
{

    private $orders;
    private $deliveryMethods;

    public function __construct(OrderGuestRepository $orders, DeliveryRepository $deliveryMethods)
    {

        $this->orders = $orders;
        $this->deliveryMethods = $deliveryMethods;


    }


    /**
     * @param $id
     * @param OrderEditGuestForm $form
     * @throws \yii\web\NotFoundHttpException
     */

    public function edit($id, OrderEditGuestForm $form)
    {
        $order = $this->orders->get($id);
        $order->edit(
            $form->delivery,
            $form->index,
            $form->address,
            $form->note,
            $form->phone,
            $form->name,
            $form->status
        );



        $this->orders->save($order);


    }

    /**
     * @param $id
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */

    public function remove($id)
    {
        $order = $this->orders->get($id);
        $this->orders->remove($order);
    }







}