<?php
/**
 * Created by PhpStorm.
 * User: MaximIV
 * Date: 18.03.2018
 * Time: 15:36
 */
namespace shop\services\manage\shop;

use shop\entities\shop\Brand;
use shop\forms\manage\shop\BrandForm;
use shop\repositories\shop\BrandRepository;
use shop\repositories\shop\ProductRepository;

class BrandManageService
{



    private $brands;
    private $products;

   public function __construct(BrandRepository $brands, ProductRepository $products)
   {
        $this->brands = $brands;
        $this->products = $products;

   }



   public function create(BrandForm $brandForm)
   {
        $brand = Brand::create(
            $brandForm->name,
            $brandForm->meta_title,
            $brandForm->meta_description,
            $brandForm->meta_keywords
        );

        $this->brands->save($brand);

        return $brand;

   }


   public function edit($id, BrandForm $form)
   {
       $brand = $this->brands->get($id);

       $brand->edit(

           $form->name,
           $form->meta_title,
           $form->meta_description,
           $form->meta_keywords
       );

       $this->brands->save($brand);

   }



   public function remove($id)
   {
       $brand = $this->brands->get($id);

        if($this->products->existsByBrand($brand->id)){

            throw new \DomainException('Невозможно удалить бренд с продуктами.');
        }

        $this->brands->remove($brand);
   }




}