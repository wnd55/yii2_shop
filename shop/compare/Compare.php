<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 09.02.19
 * Time: 23:57
 */

namespace shop\compare;

use Yii;
use shop\entities\shop\product\Product;

class Compare
{
    private $compareProduct;


    /**
     * @param $id
     */
    public function addCompareProduct($id)
    {
        $product  = Product::find()->andWhere(['id' => $id])->one();

        $this->loadCompareProduct();

        $id = md5(serialize([$product->id, $product->code]));

        foreach ($this->compareProduct as $i => $item) {

            if ($item['id'] == $id) {

                return;
            }
        }

        $this->compareProduct[] = [

            'id' => $id,
            'productId' => $product->id,
            'productName' => $product->name,
            'productDescription' => $product->description,
            'variant' => $product->productVariant->name . ' - ' . $product->productVariant->size,
            'price_new' => $product->productVariant->price_new,
            'sale' => isset($product->productDiscountActive) ? $product->productDiscountActive->percent : null,
            'price_new_sale' => isset($product->productDiscountActive) ? round($product->productVariant->price_new - ($product->productVariant->price_new * $product->productDiscountActive->percent / 100)) : null,

        ];

        $this->saveCompareProduct();

    }


    /**
     *
     */

    public function clear()
    {
        $this->compareProduct = [];

        $this->saveCompareProduct();
    }



    /**
     *
     */

    public function saveCompareProduct()
    {

        Yii::$app->session->set('compare', $this->compareProduct);

    }

    /**
     * @return mixed
     */

    public function loadCompareProduct()
    {
        if ($this->compareProduct === null) {

            return $this->compareProduct = \Yii::$app->session->get('compare', []);
        }

    }


}