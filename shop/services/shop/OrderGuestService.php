<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 28.05.18
 * Time: 19:23
 */

namespace shop\services\shop;


use shop\cart\Cart;
use shop\entities\shop\order\OrderGuest;
use shop\entities\shop\order\OrderGuestItem;
use shop\entities\shop\product\Product;
use shop\forms\shop\order\OrderGuestForm;
use shop\repositories\shop\DeliveryRepository;
use shop\repositories\shop\OrderGuestRepository;
use shop\repositories\shop\ProductRepository;
use shop\services\TransactionManager;
use yii\mail\MailerInterface;

class OrderGuestService
{

    private $cart;
    private $orders;
    private $products;
    private $deliveryMethods;
    private $transaction;


    public function __construct(
        CartService $cart, OrderGuestRepository $orders, ProductRepository $products, DeliveryRepository $deliveryMethods, TransactionManager $transaction


    )
    {
        $this->cart = $cart;
        $this->orders = $orders;
        $this->products = $products;
        $this->deliveryMethods = $deliveryMethods;
        $this->transaction = $transaction;


    }

    /**
     * @param OrderGuestForm $form
     * @param $cart
     * @param $totalCount
     * @return \shop\repositories\shop\OrderGuestRepository
     */
    public function checkoutGuest(OrderGuestForm $form, $cart, $totalCount)
    {
        /**
         * @var $order OrderGuest
         * @var $newOrderGuest OrderGuest
         * @var $orderItem OrderGuestItem
         * @var $product Product
         */
        $order = OrderGuest::create($form->delivery, $form->note, $form->phone, $form->name, $form->index, $form->address, $form->order_number);

        $delivery = $this->deliveryMethods->get($form->delivery);

        $order->delivery_method_name = $delivery->name;
        $order->delivery_cost = $delivery->cost;
        $order->cost = $totalCount + $delivery->cost;

        $newOrderGuest = $this->orders->save($order);


        foreach ($cart as $i => $item) {

            $productId = (int)$item['productId'];
            $product = $this->products->get($productId);

            $orderItem = OrderGuestItem::create(
                $newOrderGuest,
                $product,
                $item['price_new_sale'] ? $item['price_new_sale'] : $item['dimensionPrise'],
                $item['quantity'],
                $item['dimensionName']
            );

            $orderItem->save();

        }
        $this->cart->clear();


        return $newOrderGuest;
    }

}