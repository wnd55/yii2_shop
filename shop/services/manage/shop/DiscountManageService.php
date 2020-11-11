<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 18.04.19
 * Time: 11:05
 */

namespace shop\services\manage\shop;

use shop\entities\shop\Discount;
use shop\entities\shop\DiscountItem;
use shop\forms\manage\shop\DiscountForm;
use shop\forms\manage\shop\DiscountItemForm;
use shop\repositories\shop\DiscountRepository;

class  DiscountManageService
{

    public $discounts;

    public function __construct(DiscountRepository $discountRepository)
    {
        $this->discounts = $discountRepository;

    }


    public function create(DiscountForm $discountForm, DiscountItemForm $discountItemForm)
    {

        $discount = Discount::create(
            $discountForm->name,
            $discountForm->percent,
            $discountForm->from_date,
            $discountForm->to_date,
            $discountForm->active,
            $discountForm->sort
        );

        $this->discounts->save($discount);

        if (isset($discountItemForm)) {

            foreach ($discountItemForm->productId as $item)

                DiscountItem::create($discount->id, $item)->save();

        }


        return $discount;


    }


    public function edit($id, DiscountForm $discountForm, DiscountItemForm $discountItemForm)
    {
        $discount = $this->discounts->get($id);

        $discountItems = DiscountItem::find()->where(['discount_id' => $discount->id])->all();

        foreach ($discountItems as $item) {

            $item->delete();

        }

        $discount->edit(

            $discountForm->name,
            $discountForm->percent,
            $discountForm->from_date,
            $discountForm->to_date,
            $discountForm->active,
            $discountForm->sort
        );

        $this->discounts->save($discount);

        if (isset($discountItemForm)) {

            foreach ($discountItemForm->productId as $item)

                DiscountItem::create($discount->id, $item)->save();

        }


        return $discount;


    }


    public function remove($id)
    {
        $discount = $this->discounts->get($id);

        $this->discounts->remove($discount);


    }
}