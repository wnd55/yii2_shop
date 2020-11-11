<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 24.01.19
 * Time: 12:15
 */

namespace shop\readModels\shop;

use shop\entities\shop\Brand;

class BrandReadRepository
{

    public function find($id)
    {
        return Brand::findOne($id);
    }


    public function findBrandBySlug($slug)
    {

        return Brand::findOne(['slug' => $slug]);

    }

}