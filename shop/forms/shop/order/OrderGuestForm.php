<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 28.05.18
 * Time: 19:36
 */

namespace shop\forms\shop\order;


use shop\entities\shop\Delivery;
use shop\entities\shop\order\OrderGuest;
use shop\entities\shop\order\OrderUser;
use shop\entities\shop\product\Product;
use shop\helpers\PriceHelper;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class OrderEditGuestForm
 * @package shop\forms\shop\order
 *
 */
class OrderGuestForm extends Model
{


    public $delivery;
    public $index;
    public $address;
    public $note;
    public $phone;
    public $name;
    public $order_number;
    public static $i = 1000;

    /**
     * OrderGuestForm constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {


        if (OrderGuest::find()->exists() || OrderUser::find()->exists()) {

            $order = array();

            $order_number_guest_all = OrderGuest::find()->max('order_number') + 1;
            $order[] = substr( $order_number_guest_all, -4);

            $order_number_user_all = OrderUser::find()->max('order_number') + 1;
            $order[] = substr( $order_number_user_all, -4);

            $this->order_number = rand(1000,10000).max($order);



        } else {

            $this->order_number = rand(1000,10000) . self::$i;
        }

        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['note'], 'string'],
            [['delivery'], 'integer'],
            [['index', 'address', 'delivery'], 'required'],
            [['index'], 'string', 'min' => 6, 'max' => 6],
            [['address'], 'string'],
            [['phone', 'name'], 'required'],
            [['phone', 'name'], 'string', 'max' => 255],
            [['order_number'], 'unique', 'targetClass' => OrderGuest::class],
            [['order_number'], 'unique', 'targetClass' => OrderUser::class],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
            'username' => 'Username',
            'delivery' => 'Варианты доставки',
            'delivery_method_name' => 'Delivery Method Name',
            'delivery_cost' => 'Стоимость доставки',
            'payment_method' => 'Payment Method',
            'cost' => 'Cost',
            'note' => 'Комментарии',
            'current_status' => 'Статус',
            'cancel_reason' => 'Причина отмены заказа',
            'phone' => 'Телефон',
            'name' => 'ФИО',
            'index' => 'Индекс',
            'address' => 'Адрес доставки',
            'order_number' =>'Номер заказа гостя'
        ];
    }


    /**
     * @return array
     */

    public function deliveryMethodsList()
    {
        $delivery = Delivery::find()->orderBy('sort')->all();

        return ArrayHelper::map($delivery, 'id', function (Delivery $delivery) {
            return $delivery->name . ' (' . $delivery->cost . ')';
        });
    }


}