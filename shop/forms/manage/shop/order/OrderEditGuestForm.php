<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 28.05.18
 * Time: 19:36
 */

namespace shop\forms\manage\shop\order;


use function PHPSTORM_META\map;
use shop\entities\shop\Delivery;
use shop\entities\shop\Order\OrderGuest;
use shop\helpers\PriceHelper;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class OrderEditGuestForm
 * @package shop\forms\shop\order
 *
 */
class OrderEditGuestForm extends Model
{


    public $delivery;
    public $index;
    public $address;
    public $note;
    public $phone;
    public $name;
    public $status;


    public function __construct(OrderGuest $orderUser, array $config = [])
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
            'username' => 'Username',
            'delivery' => 'Варианты доставки',
            'delivery_method_name' => 'Доставка',
            'delivery_cost' => 'Стоимость доставки',
            'payment_method' => 'Метод оплаты',
            'cost' => 'Полная стоимость заказаt',
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