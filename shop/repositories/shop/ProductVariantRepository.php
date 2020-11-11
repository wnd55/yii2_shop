<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 05.04.19
 * Time: 0:13
 */

namespace shop\repositories\shop;

use shop\entities\shop\product\ProductVariant;

class ProductVariantRepository
{

    public function get($id)
    {
        if(!$productVariant = ProductVariant::findOne($id)){

            throw new \DomainException('Variant is not found');
        }

        return$productVariant;
    }

    public function save(ProductVariant $productVariant)
    {

        if(!$productVariant->save()){

            throw new \RuntimeException('Saving error');
        }

    }

    public function remove(ProductVariant $productVariant)
    {
        if(!$productVariant->delete()){

            throw new \DomainException('Removing error');
        }


    }






}