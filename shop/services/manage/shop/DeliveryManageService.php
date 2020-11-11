<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 28.05.18
 * Time: 12:53
 */

namespace shop\services\manage\shop;


use shop\entities\shop\Delivery;

use shop\forms\manage\shop\DeliveryForm;
use shop\repositories\shop\DeliveryRepository;

class DeliveryManageService
{

    private $methods;

    public function __construct(DeliveryRepository $methods)
    {
        $this->methods = $methods;
    }


    public function create(DeliveryForm $form)
    {
        $method = Delivery::create(

            $form->name,
            $form->cost,
            $form->minWeight,
            $form->maxWeight,
            $form->sort

        );

        $this->methods->save($method);
        return $method;

    }

    public function edit($id, DeliveryForm $form)
    {
        $method = $this->methods->get($id);
        $method->edit(
            $form->name,
            $form->cost,
            $form->minWeight,
            $form->maxWeight,
            $form->sort
        );
        $this->methods->save($method);
    }

    public function remove($id)
    {
        $method = $this->methods->get($id);
        $this->methods->remove($method);
    }


}