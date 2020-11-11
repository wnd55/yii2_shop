<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 27.03.19
 * Time: 12:16
 */

namespace shop\entities\user;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property integer $user_id
 * @property integer $bonus
 * @property integer $created_at
 * @property integer $updated_at
 */
class Bonus extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%user_bonus}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [

            TimestampBehavior::class
        ];


    }


    /**
     * @param $userId
     * @param $userBonus
     * @return static
     */
    public static function create($userId, $userBonus)
    {
        $item = new static();
        $item->user_id = $userId;
        $item->bonus = $userBonus;
        $item->save();


    }

   

}