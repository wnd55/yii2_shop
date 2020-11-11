<?php
/**
 * Created by PhpStorm.
 * User: MaximIV
 * Date: 18.03.2018
 * Time: 15:28
 */
namespace shop\repositories\shop;

use shop\entities\shop\Brand;


class BrandRepository
{
    public function get($id)
    {
        if(!$brand = Brand::findOne($id)){

            throw new \DomainException('Brand is not found');
        }

        return $brand;
    }

    public function save(Brand $brand)
    {

        if(!$brand->save()){

            throw new \RuntimeException('Saving error');
        }

    }

    public function remove(Brand $brand)
    {
        if(!$brand->delete()){

            throw new \DomainException('Removing error');
        }


    }



}