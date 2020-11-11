<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 05.04.19
 * Time: 0:16
 */

namespace shop\services\manage\shop;


use shop\entities\shop\product\ProductVariant;
use shop\forms\manage\shop\product\ProductVariantForm;
use shop\repositories\shop\ProductRepository;
use shop\repositories\shop\ProductVariantRepository;

class ProductVariantManageService
{


    private $productVariant;
    private $products;

    /**
     * ProductVariantManageService constructor.
     * @param ProductVariantRepository $productVariant
     * @param ProductRepository $products
     */
    public function __construct(ProductVariantRepository $productVariant, ProductRepository $products)
    {
        $this->productVariant = $productVariant;
        $this->products = $products;

    }

    /**
     * @param ProductVariantForm $form
     * @return static
     */
    public function create(ProductVariantForm $form)
    {

        $productVariant = ProductVariant::create(

            $form->name,
            $form->size,
            $form->price_new,
            $form->price_old
        );

         $this->productVariant->save($productVariant);

        return $productVariant;

    }


    /**
     * @param $id
     * @param ProductVariantForm $form
     */
    public function edit($id, ProductVariantForm $form)
    {


        $productVariant = $this->productVariant->get($id);
        $productVariant->edit(

            $form->name,
            $form->size,
            $form->price_new,
            $form->price_old

        );

        $this->productVariant->save($productVariant);


    }

    /**
     * @param $id
     */
    public function remove($id)
    {

        $productVariant = $this->productVariant->get($id);

        $this->productVariant->remove($productVariant);



    }



}