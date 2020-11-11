<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 19.03.18
 * Time: 12:49
 */

namespace shop\repositories\shop;

use shop\entities\shop\product\CategoryAssignment;
use shop\entities\shop\product\Photo;
use shop\entities\shop\product\Product;

/**
 * Class ProductRepository
 * @package shop\repositories\shop
 */

class ProductRepository
{


    public function get($id)
    {
        if (!$product = Product::findOne($id)) {
            throw new \DomainException('Product is not found.');
        }
        return $product;
    }


    public function save(Product $product)
    {
        if (!$product->save()) {
            throw new \RuntimeException('Saving error.');
        }

    }

    /**
     * @param Product $product
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */

    public function remove(Product $product)
    {
        if (!$product->delete()) {
            throw new \RuntimeException('Removing error.');
        }


    }


    public function existsByBrand($id)
    {

        return Product::find()->andWhere(['brand_id' => $id])->exists();


    }

        public function updateAttributes ($id)
        {
            $sort =  Photo::find()->where(['product_id' => $id])->Min('sort');

           $this->updateAttributes(['main_photo_id' => $sort]);

        }



}