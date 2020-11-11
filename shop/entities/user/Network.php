<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 04.01.19
 * Time: 18:33
 */

namespace shop\entities\user;

use yii\db\ActiveRecord;

/**
 * @property integer $user_id
 * @property string $identity
 * @property string $network
 */
class Network extends ActiveRecord
{

    /**
     * @param $network
     * @param $identity
     * @param $userId
     * @return static
     */
    public static function create($network, $identity, $userId)
    {

        if (!(empty($network)) && !empty($identity)) {

            $item = new static();

            $item->network = $network;
            $item->identity = $identity;
            $item->user_id = $userId;

            return $item;


        }


    }

    /**
     * @param $network
     * @param $identity
     * @return bool
     */
    public function isFor($network, $identity)
    {
        return $this->network === $network && $this->identity === $identity;
    }

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%user_networks}}';
    }


}