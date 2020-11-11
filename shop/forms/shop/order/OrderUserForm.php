<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 17.02.19
 * Time: 15:37
 */

namespace shop\forms\shop\order;


use shop\entities\shop\Delivery;
use shop\entities\shop\order\OrderGuest;
use shop\entities\shop\order\OrderUser;
use shop\entities\user\User;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class OrderUserForm extends Model
{
    public static $i = 1000;
    public $user_id;
    public $delivery;
    public $index;
    public $address;
    public $note;
    public $phone;
    public $name;
    public $order_number;
    protected $_user;

    public function __construct(User $user, array $config = [])
    {
        if (OrderGuest::find()->exists() || OrderUser::find()->exists()) {

            $order = array();

            $last_guest_order = OrderGuest::find()->orderBy("created_at DESC")->one();
            if (isset($last_guest_order)) {
                $order_number_guest_all = (int)$last_guest_order->order_number + 1;
                $order[] = substr($order_number_guest_all, -4);
            }
            $last_user_order = OrderUser::find()->orderBy("created_at DESC")->one();
            if (isset($last_user_order)) {
                $order_number_user_all = (int)$last_user_order->order_number + 1;
                $order[] = substr($order_number_user_all, -4);
            }

            $this->order_number = rand(1000, 10000) . max($order);


            $this->phone = $user->phone;


        } else {


            $this->order_number = rand(1000, 10000) . self::$i;
        }

        parent::__construct($config);
    }


    public function rules()
    {
        return [
            [['note'], 'string'],
            [['delivery', 'user_id'], 'integer'],
            [['index', 'address', 'delivery',], 'required'],
            [['index'], 'string', 'min' => 6, 'max' => 6],
            [['address'], 'string'],
            [['name'], 'required'],
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
            'delivery' => 'Варианты доставки',
            'delivery_method_name' => 'Delivery Method Name',
            'delivery_cost' => 'Delivery Cost',
            'payment_method' => 'Payment Method',
            'cost' => 'Cost',
            'note' => 'Комментарии',
            'current_status' => 'Current Status',
            'cancel_reason' => 'Cancel Reason',
            'phone' => 'Телефон',
            'name' => 'ФИО',
            'index' => 'Индекс',
            'address' => 'Адрес доставки',
            'order_number' => 'Номер заказа',
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