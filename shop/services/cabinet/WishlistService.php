<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 31.01.19
 * Time: 12:48
 */

namespace shop\services\cabinet;

use shop\repositories\shop\ProductRepository;
use shop\repositories\UserRepository;

class WishlistService
{
    private $users;
    private $products;


    public function __construct(UserRepository $users, ProductRepository $products)
    {
        $this->users = $users;
        $this->products = $products;
    }



    public function add($userId, $productId)
    {
        $user = $this->users->get($userId);
        $product = $this->products->get($productId);
        $user->addToWishList($userId, $product->id);


    }


    public function remove($userId, $productId)
    {
        $user = $this->users->get($userId);
        $product = $this->products->get($productId);
        $user->removeFromWishList($product->id);

    }


}