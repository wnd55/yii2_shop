<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 11.04.19
 * Time: 22:07
 */

namespace shop\entities\shop;


use shop\entities\user\User;
use yii\db\ActiveRecord;


/**
 * @property integer $id
 * @property string $name
 * @property integer $percent
 * @property bool $status
 * @property integer $created_at
 */
class DiscountUser extends ActiveRecord
{


    const DISCOUNT_STATUS_ACTIVE = 1;
    const  DISCOUNT_STATUS_DRAFT = 0;

    public static function tableName()
    {
        return '{{%shop_discounts_users}}';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Дата создания',
            'percent' => 'Процент скидки',
            'name' => 'Название скидки',
            'status' => 'Статус',
        ];
    }


    /**
     * @param $percent
     * @param $name
     * @param $status
     * @return static
     */
    public static function create($name, $percent, $status)
    {

        $discount = new static();

        $discount->created_at = time();

        $discount->name = $name;
        $discount->percent = $percent;
        $discount->status = $status;


        return $discount;


    }


    public function edit($name, $percent, $status)
    {

        $this->name = $name;
        $this->percent = $percent;
        $this->status = $status;


    }

    public function isActiveDiscountUser()
    {

       return $this->status == self::DISCOUNT_STATUS_ACTIVE;

    }

    public function getDiscountsUsersItems()

    {
        return $this->hasMany(DiscountUserItem::class, ['discount_user_id' => 'id']);

    }


    public function getDiscountUser()
    {

        return $this->hasOne(User::class, ['id' => 'discount_user_id'])->via('discountsUsersItems');


    }
}