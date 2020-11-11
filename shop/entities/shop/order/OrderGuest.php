<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 10.02.19
 * Time: 14:26
 */


namespace shop\entities\shop\order;


use shop\entities\shop\Delivery;
use shop\forms\shop\order\OrderGuestForm;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;


/**
 * OrderGuest model
 *
 * @property integer $id
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
 * @property string $customer_phone
 * @property string $customer_name
 * @property string $delivery_index
 * @property string $delivery_address
 * @property integer $order_number
 */
class OrderGuest extends ActiveRecord
{

    const NEW_ORDER = 1;
    const PAID = 2;
    const SENT = 3;
    const COMPLETED = 4;
    const CANCELLED = 5;
    const CANCELLED_BY_CUSTOMER = 6;

    const USER = 'guest';

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
        return '{{%shop_orders_guest}}';
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Дата создания',
            'username' => 'Username',
            'delivery_method_id' => 'Доставка ID',
            'delivery_method_name' => 'Доставка',
            'delivery_cost' => 'Доставка стоимость',
            'payment_method' => 'Метод оплаты',
            'cost' => 'Полная стоимость заказа',
            'note' => 'Земетки',
            'current_status' => 'Статус заказа',
            'cancel_reason' => 'Отмена заказа',
            'customer_phone' => 'Клиент телефон',
            'customer_name' => 'Клиент ФИО',
            'delivery_index' => 'Клиент индекс доставки',
            'delivery_address' => 'Клиент адрес доставки',
            'order_number' => 'Номер заказа',
        ];
    }



    public static function create($delivery, $note, $phone, $name, $index, $address, $order_number)
    {
        $orderGuest = new static;

        $orderGuest->created_at = time();
        $orderGuest->username = self::USER;
        $orderGuest->delivery_method_id = $delivery;
        $orderGuest->payment_method = self::PAYMENT_METHOD_CDEK;
        $orderGuest->note = $note;
        $orderGuest->current_status = self::NEW_ORDER;
        $orderGuest->customer_phone = $phone;
        $orderGuest->delivery_index = $index;
        $orderGuest->customer_name = $name;
        $orderGuest->delivery_address = $address;
        $orderGuest->order_number = $order_number;



        return $orderGuest;


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


    ##########################

    public function getDelivery()
    {

        return $this->hasOne(Delivery::class, ['id' => 'delivery_method_id']);
    }


    public function getItems()
    {

        return $this->hasMany(OrderGuestItem::class, ['order_id' => 'id']);
    }


}
