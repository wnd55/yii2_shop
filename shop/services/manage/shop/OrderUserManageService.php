<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 21.02.19
 * Time: 20:34
 */

namespace shop\services\manage\shop;


use shop\cart\Discount;
use shop\entities\shop\order\OrderUser;
use shop\entities\shop\order\OrderUserItem;
use shop\entities\shop\product\Product;
use shop\forms\manage\shop\order\OrderCreateUserForm;
use shop\forms\manage\shop\order\OrderEditUserForm;
use shop\repositories\shop\DeliveryRepository;
use shop\repositories\shop\OrderUserRepository;
use shop\repositories\shop\ProductRepository;
use shop\repositories\UserRepository;
use shop\entities\user\Bonus;

class OrderUserManageService
{

    private $orders;
    private $deliveryMethods;
    private $products;
    private $users;
    private $discount;

    public function __construct(UserRepository $userRepository,  Discount $discount, OrderUserRepository $orderUserRepository, DeliveryRepository $deliveryRepository, ProductRepository $productRepository)
    {

        $this->orders = $orderUserRepository;
        $this->deliveryMethods = $deliveryRepository;
        $this->products = $productRepository;
        $this->users = $userRepository;
        $this->discount = $discount;


    }


    /**
     * @param $id
     * @param OrderEditUserForm $form
     * @throws \yii\web\NotFoundHttpException
     */

    public function edit($id, OrderEditUserForm $form)
    {
        /**
         * @var $order OrderUser
         */
        $order = $this->orders->get($id);
        $order->edit(
            $form->delivery,
            $form->index,
            $form->address,
            $form->note,
            $form->phone,
            $form->name,
            $form->status
        );


        $this->orders->save($order);


    }

    /**
     * @param $id
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */

    public function remove($id)
    {
        $order = $this->orders->get($id);
        $this->orders->remove($order);
    }


    public function createUserOrder(OrderCreateUserForm $createUserForm, $productsForm)
    {

        /**
         * @var $product Product
         */

        $order = OrderUser::createUserOrder(

            $createUserForm->created_at,
            $createUserForm->user_id,
            $createUserForm->delivery,
            $createUserForm->note,
            $createUserForm->phone,
            $createUserForm->name,
            $createUserForm->index,
            $createUserForm->address,
            $createUserForm->order_number

        );

        $delivery = $this->deliveryMethods->get($createUserForm->delivery);
        $order->delivery_method_name = $delivery->name;
        $order->delivery_cost = $delivery->cost;

        $user = $this->users->get($createUserForm->user_id);
        $userBonus = $user->bonus ?  $user->bonus->bonus : 0;

        $newOrder = $this->orders->save($order);


        $totalCount = 0;

        foreach ($productsForm as $item) {

            $product = $this->products->get((int)$item->product);
            $priceProduct = isset($product->productDiscountActive) ? round($product->productVariant->price_new - ($product->productVariant->price_new * $product->productDiscountActive->percent / 100)) :$product->productVariant->price_new;
            $priceProductQuantity = $priceProduct * (int)$item->quantity;

            $orderItem = OrderUserItem::createUser(
                $newOrder,
                $product,
                $priceProduct,
                $item->quantity,
                $product->productVariant->name
            );

            $orderItem->save();

            $totalCount += $priceProductQuantity;


        }


        $totalCountWithBonus = $totalCount - $userBonus;
        $totalCountWithBonusAndDiscountUser = $this->discount->userDiscount($user, $totalCountWithBonus);


        $newOrder->updateAttributes(['cost' => $totalCountWithBonusAndDiscountUser + $newOrder->delivery_cost]);

        $user->addBonus($user->id, $totalCount);

        return $newOrder;




    }


}