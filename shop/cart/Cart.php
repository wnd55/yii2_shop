<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 06.02.19
 * Time: 16:45
 */

namespace shop\cart;

use shop\entities\shop\product\Product;
use shop\entities\user\User;
use Yii;
use shop\entities\user\Bonus;

/**
 * Class Cart
 * @package shop\cart
 * @var $product Product
 */
class Cart
{

    public $cartItems;

    /**
     * @param $id
     * @param $quantity
     * @param $dimensionId
     * @param $dimensionName
     * @param $dimensionPrice
     */
    public function addCart($id, $quantity, $dimensionId, $dimensionName, $dimensionPrice)
    {
        /**
         * @var $product Product
         */

        $product = Product::find()->where(['id' => $id])->one();

        $this->loadCartItems();

        $id = md5(serialize([$product->id, $dimensionId]));

        foreach ($this->cartItems as $i => $item) {

            if ($item['id'] == $id) {

                $this->cartItems[$i]['quantity'] += $quantity;


                $this->saveCartItems();

                return;
            }
        }

        $this->cartItems[] = [

            'id' => $id,
            'productId' => $product->id,
            'productName' => $product->name,
            'variant' => $product->productVariant->name . ' - ' . $product->productVariant->size,
            'price_new' => $product->productVariant->price_new,
            'price_old' => $product->productVariant->price_old,
            'sale' => isset($product->productDiscountActive) ? $product->productDiscountActive->percent : null,
            'price_new_sale' => isset($product->productDiscountActive) ? round($dimensionPrice - ($dimensionPrice * $product->productDiscountActive->percent / 100)) : null,
            'quantity' => $quantity,
            'dimensionId' => $dimensionId,
            'dimensionName'=> $dimensionName,
            'dimensionPrise' => $dimensionPrice


        ];


        $this->saveCartItems();


    }

    /**
     * @param $id
     * @param $quantity
     */
    public function addQuantity($id, $quantity)
    {
        $this->loadCartItems();

        foreach ($this->cartItems as $i => $item) {

            if ($item['id'] == $id) {

                $this->cartItems[$i]['quantity'] = $quantity;

                $this->saveCartItems();
                return;
            }
        }


    }

    /**
     * @return int
     */
    public function totalCount()
    {

        $this->loadCartItems();

        $totalCount = 0;

        foreach ($this->cartItems as $i => $item) {

            $totalCount += (int)($item['price_new_sale'] ? $item['price_new_sale'] : $item['dimensionPrise']) * (int)$this->cartItems[$i]['quantity'];
        }

        return $totalCount;

    }

    /**
     * @param User $user
     * @return int
     */

    public function totalCountWithBonus(User $user)
    {

        $totalCount = $this->totalCount();


        if ($user->bonus) {

            $bonusUser = $user->bonus->bonus;
            return $totalCountWithBonus = $totalCount - $bonusUser;
        } else {

            return $totalCount;

        }


    }


    /**
     * @return int
     */
    public function getAmount()
    {
        $this->loadCartItems();

        return count($this->cartItems);
    }


    /**
     *
     */

    public function clear()
    {
        $this->cartItems = [];

        $this->saveCartItems();
    }


    /**
     * @param $id
     */
    public function remove($id)
    {
        $this->loadCartItems();

        foreach ($this->cartItems as $i => $current) {
            if ($current['id'] == $id) {

                unset($this->cartItems[$i]);
                $this->saveCartItems();
                return;
            }
        }
        throw new \DomainException('Item is not found.');
    }

    /**
     *
     */

    public function saveCartItems()
    {

        Yii::$app->session->set('cart', $this->cartItems);

    }

    /**
     * @return mixed
     */

    public function loadCartItems()
    {
        if ($this->cartItems === null) {


            return $this->cartItems = \Yii::$app->session->get('cart', []);
        }

    }
}