<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 17.02.19
 * Time: 16:16
 */

namespace shop\services\shop;

use shop\entities\shop\product\Product;
use shop\entities\user\Bonus;
use shop\entities\user\User;
use Yii;
use shop\entities\shop\order\OrderUser;
use shop\entities\shop\order\OrderUserItem;
use shop\forms\shop\order\OrderUserForm;
use shop\repositories\shop\DeliveryRepository;
use shop\repositories\shop\OrderUserRepository;
use shop\repositories\shop\ProductRepository;
use shop\services\TransactionManager;
use yii\base\Event;
use yii\mail\MailerInterface;


class OrderUserService
{


    private $cart;
    private $orders;
    private $products;
    private $deliveryMethods;
    private $transaction;
    private $mailer;
    private $event;


    public function __construct(
        CartService $cart, OrderUserRepository $orders, ProductRepository $products, DeliveryRepository $deliveryMethods,

        TransactionManager $transaction, MailerInterface $mailer, Event $event)
    {
        $this->cart = $cart;
        $this->orders = $orders;
        $this->products = $products;
        $this->deliveryMethods = $deliveryMethods;
        $this->transaction = $transaction;
        $this->mailer = $mailer;
        $this->event = $event;


    }


    public function checkoutUser($user, OrderUserForm $form, $cart, $totalCount, $totalCountWithBonusAndDiscountUser)
    {
        /**
         * @var $product Product
         * @var $user User
         *
         */

        $order = OrderUser::create($user, $form->delivery, $form->note, $form->phone, $form->name, $form->index, $form->address, $form->order_number);

        $delivery = $this->deliveryMethods->get($form->delivery);
        $order->delivery_method_name = $delivery->name;
        $order->delivery_cost = $delivery->cost;

        $order->cost = $totalCountWithBonusAndDiscountUser + $delivery->cost;

        $newOrder = $this->orders->save($order);


        foreach ($cart as $i => $item) {

            $productId = $item['productId'];
            $product = $this->products->get($productId);

            $orderItem = OrderUserItem::createUser(
                $newOrder,
                $product,
                $item['price_new_sale'] ? $item['price_new_sale'] : $item['dimensionPrise'],
                $item['quantity'],
                $item['dimensionName']
            );

            $orderItem->save();

        }


        $user->addBonus($user->id, $totalCount);

        $this->cart->clear();

        $sent = $this->mailer->compose(

            [
                'html' => 'order/order/confirmOrderUserNotificator-html',

            ],
            [
                'order' => $newOrder,
                'logo' => Yii::getAlias('@common/mail/images/Logo.png'),
                'ruble' => Yii::getAlias('@common/mail/images/ruble.png')

            ]
        )
            ->setTo($user->email)
            ->setFrom(\Yii::$app->params['adminEmail'])
            ->setSubject('Заказ на сайте new.aroma-test.ru')
            ->send();

        if (!$sent) {
            throw new \RuntimeException('Sending error.');
        }


        return $newOrder;
    }




}