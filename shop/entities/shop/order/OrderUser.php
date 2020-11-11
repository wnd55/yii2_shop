<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 16.02.19
 * Time: 23:38
 */

namespace shop\entities\shop\order;


use shop\entities\shop\Delivery;
use shop\entities\user\User;
use yii\db\ActiveRecord;

/**
 * OrderGuest model
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $created_at
 * @property string $username
 * @property integer $delivery_method_id
 * @property string $delivery_method_name
 * @property integer $delivery_cost
 * @property string $payment_method
 * @property integer $cost
 * @property string $note
 * @property integer $current_status
 * @property string $cancel_reason
 * @property integer $statuses
 * @property string $customer_phone
 * @property string $customer_name
 * @property string $delivery_index
 * @property string $delivery_address
 * @property integer $order_number
 */
class OrderUser extends ActiveRecord
{


    const NEW_ORDER = 1;
    const PAID = 2;
    const SENT = 3;
    const COMPLETED = 4;
    const CANCELLED = 5;
    const CANCELLED_BY_CUSTOMER = 6;


    const PAYMENT_METHOD_CDEK = 1;
    const PAYMENT_METHOD_COURIER = 2;
    const PAYMENT_METHOD_ONLINE_CART = 3;


    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function tableName()
    {
        return '{{%shop_orders_user}}';
    }


    public static function create($user, $delivery, $note, $phone, $name, $index, $address, $order_number)
    {
        $order = new static();

        $order->created_at = time();
        $order->user_id = $user->id;
        $order->delivery_method_id = $delivery;
        $order->payment_method = self::PAYMENT_METHOD_CDEK;
        $order->note = $note;
        $order->current_status = self::NEW_ORDER;
        $order->statuses = self::NEW_ORDER;
        $order->customer_phone = $phone;
        $order->delivery_index = $index;
        $order->customer_name = $name;
        $order->delivery_address = $address;
        $order->order_number = $order_number;

        return $order;


    }


    public function edit($delivery, $index, $address, $note, $phone, $name, $status)
    {
        $this->delivery_method_id = $delivery;
        $this->delivery_index = $index;
        $this->delivery_address = $address;
        $this->note = $note;
        $this->customer_phone = $phone;
        $this->customer_name = $name;
        $this->current_status = $status;


    }

    public static function createUserOrder($dateOrder, $userId, $delivery, $note, $phone, $name, $index, $address, $order_number)
    {
        $order = new static();

      $order->created_at = strtotime($dateOrder . date("H:i:s"));
        $order->user_id = $userId;
        $order->delivery_method_id = $delivery;
        $order->payment_method = self::PAYMENT_METHOD_CDEK;
        $order->note = $note;
        $order->current_status = self::NEW_ORDER;
        $order->customer_phone = $phone;
        $order->customer_name = $name;
        $order->delivery_index = $index;
        $order->delivery_address = $address;
        $order->order_number = $order_number;
        $order->cost = 0;


        return $order;
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Дата',
            'user_id' => 'User ID',
            'delivery_method_id' => 'Delivery Method ID',
            'delivery_method_name' => 'Доставка',
            'delivery_cost' => 'Стоимость доставки',
            'payment_method' => 'Вариант оплаты',
            'cost' => 'Стоимость заказа',
            'note' => 'Комментарии',
            'current_status' => 'Статус заказа',
            'cancel_reason' => 'Причина отмены',
            'statuses' => 'Statuses',
            'customer_phone' => 'Телефон',
            'customer_name' => 'ФИО',
            'delivery_index' => 'Индекс',
            'delivery_address' => 'Адрес доставки',
            'order_number' => 'Номер заказа',
        ];
    }




    ##########################

    public function getDelivery()
    {

        return $this->hasOne(Delivery::class, ['id' => 'delivery_method_id']);
    }


    public function getItems()
    {

        return $this->hasMany(OrderUserItem::class, ['order_id' => 'id']);
    }


    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);

    }

}
