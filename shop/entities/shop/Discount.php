<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 10.05.18
 * Time: 18:30
 */

namespace shop\entities\shop;

use shop\entities\shop\product\Product;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property integer $percent
 * @property string $name
 * @property string $from_date
 * @property string $to_date
 * @property bool $active
 * @property integer $sort
 * @property DiscountItem[] $discountItems
 * @property Product[] $discountProduct
 */
class Discount extends ActiveRecord
{

    const DISCOUNT_ACTIVE = 1;
    const DISCOUNT_DRAFT = 0;

    public static function tableName()
    {
        return '{{%shop_discounts}}';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'percent' => 'Процент скидки',
            'name' => 'Название скидки',
            'from_date' => 'С ',
            'to_date' => 'По',
            'active' => 'Статус',
            'sort' => 'Сортировка',
        ];
    }

    public static function create($name, $percent, $fromDate, $toDate, $active, $sort)
    {
        $discount = new static();

        $discount->name = $name;
        $discount->percent = $percent;
        $discount->from_date = $fromDate;
        $discount->to_date = $toDate;
        $discount->active = $active;
        $discount->sort = $sort;

        return $discount;
    }


    public function edit($name, $percent, $fromDate, $toDate, $active, $sort)
    {
        $this->name = $name;
        $this->percent = $percent;
        $this->from_date = $fromDate;
        $this->to_date = $toDate;
        $this->active = $active;
        $this->sort = $sort;

    }


    public function activate()
    {
        $this->active = true;
    }

    public function draft()
    {
        $this->active = false;
    }

    public function isEnabled()
    {
        return true;
    }



    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiscountItems()
    {
        return $this->hasMany(DiscountItem::class, ['discount_id' => 'id']);

    }

    public function getDiscountProduct()
    {

        return $this->hasMany(Product::class, ['id' => 'product_id'])->via('discountItems');
    }
}
