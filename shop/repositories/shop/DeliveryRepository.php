<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 28.05.18
 * Time: 12:48
 */

namespace shop\repositories\shop;


use shop\entities\shop\Delivery;
use yii\web\NotFoundHttpException;

class DeliveryRepository
{


    /**
     * @param integer $id
     * @return Delivery the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */


    public function get($id)
    {

        if (!$method = Delivery::findOne($id)) {


            throw new NotFoundHttpException('DeliveryMethod is not found.');

        }

        return $method;


    }


    /**
     * @param Delivery $method
     */
    public function save(Delivery $method)
    {
        if (!$method->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    /**
     * @param Delivery $method
     */


    public function remove(Delivery $method)
    {
        if (!$method->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }


    /**
     * @param $name
     * @return null|static
     */

    public function findByName($name)
    {
        return Delivery::findOne(['name' => $name]);
    }


}