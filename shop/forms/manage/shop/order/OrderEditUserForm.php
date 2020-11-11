<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 21.02.19
 * Time: 20:39
 */

namespace shop\forms\manage\shop\order;

use shop\entities\shop\Delivery;
use shop\entities\shop\order\OrderUser;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class OrderEditUserForm extends Model
{

    public $delivery;
    public $index;
    public $address;
    public $note;
    public $phone;
    public $name;
    public $status;


    public function __construct(OrderUser $orderUser, array $config = [])
    {
        $this->delivery = $orderUser->delivery_method_id;
        $this->index = $orderUser->delivery_index;
        $this->address = $orderUser->delivery_address;
        $this->note = $orderUser->note;
        $this->phone = $orderUser->customer_phone;
        $this->name = $orderUser->customer_name;
        $this->status = $orderUser->current_status;



        parent::__construct($config);
    }



    public function rules()
    {
        return [
            [['note'], 'string'],
            [['delivery', 'status'], 'integer'],
            [['index', 'address'], 'required'],
            [['index'], 'string', 'min' => 6, 'max' => 6],
            [['address'], 'string'],
            [['phone', 'name'], 'required'],
            [['phone', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Дата создания',

            'delivery' => 'Варианты доставки',
            'delivery_method_name' => 'Доставка',
            'delivery_cost' => 'Стоимость доставки',
            'payment_method' => 'Метод оплаты',
            'cost' => 'Полная стоимость заказа',
            'note' => 'Комментарии',
            'current_status' => 'Статус заказа',
            'cancel_reason' => 'Причина отмены',
            'phone' => 'Телефон',
            'name' => 'ФИО',
            'index' => 'Индекс',
            'address' => 'Адрес доставки',
        ];
    }


    /**
     * @return array
     */

    public function deliveryMethodsList()
    {
        $delivery = Delivery::find()->orderBy('sort')->all();

        return ArrayHelper::map($delivery, 'id', function (Delivery $delivery) {
            return $delivery->name . ' (' . $delivery->cost .')';
        });
    }




}