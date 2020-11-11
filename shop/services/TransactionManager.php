<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 28.05.18
 * Time: 15:11
 */

namespace shop\services;



class TransactionManager
{

    /**
     * @param callable $function
     * @throws \Exception
     * @throws \yii\db\Exception
     */

    public function wrap(callable $function)
    {

        $transaction = \Yii::$app->db->beginTransaction();

        try {

            $function();
            $transaction->commit();


        } catch (\Exception $e) {

            $transaction->rollBack();
            throw $e;
        }


    }


}