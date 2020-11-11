<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 29.05.19
 * Time: 20:17
 */

namespace shop\forms\manage\shop\order;

use yii\base\Model;
use shop\entities\shop\Delivery;
use shop\entities\shop\order\OrderGuest;
use shop\entities\shop\order\OrderUser;
use shop\entities\user\User;
use yii\helpers\ArrayHelper;


class OrderCreateUserForm extends Model
{
    public $user_id;
    public $delivery;
    public $index;
    public $address;
    public $note;
    public $phone;
    public $name;
    public $status;
    public $created_at;
    public $order_number;
    public static $i = 1000;

    public function __construct(array $config = [])
    {
        if (OrderGuest::find()->exists() || OrderUser::find()->exists()) {

            $order = array();

            $last_guest_order = OrderGuest::find()->orderBy("created_at DESC")->one();
            $order_number_guest_all = (int)$last_guest_order->order_number + 1;
            $order[] = substr($order_number_guest_all, -4);

            $last_user_order = OrderUser::find()->orderBy("created_at DESC")->one();
            $order_number_user_all = (int)$last_user_order->order_number + 1;
            $order[] = substr($order_number_user_all, -4);


            $this->order_number = rand(1000, 10000) . max($order);





        } else {


            $this->order_number = rand(1000, 10000) . self::$i;
        }

        parent::__construct($config);
    }




    public function rules()
    {
        return [
            [['note'], 'string'],
            ['note', 'default', 'value' => 'Нет'],
            [['delivery', 'user_id', 'status'], 'integer'],
            [['index', 'address', 'delivery',], 'required'],
            [['index'], 'string', 'min' => 6, 'max' => 6],
            [['address'], 'string'],
            [['name'], 'required'],
            [['phone', 'name'], 'string', 'max' => 255],
            [['order_number'], 'unique', 'targetClass' => OrderGuest::class],
            [['order_number'], 'unique', 'targetClass' => OrderUser::class],
            [['created_at'], 'date', 'format' => 'php:Y-m-d' ]
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Клиент',
            'created_at' => 'Дата заказа',
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


    public function orderUserList()
    {

        $users = User::find()->where(['status' => User::STATUS_ACTIVE])->all();
        return ArrayHelper::map($users, 'id', function (User $users) {

            return $users->username . '(' . $users->email . ''. $users->phone.')';
        });

    }

}