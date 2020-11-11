<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 17.04.19
 * Time: 21:57
 */

namespace shop\forms\manage\shop;

use shop\entities\shop\Discount;
use shop\entities\shop\DiscountItem;
use shop\entities\shop\product\Product;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class DiscountItemForm extends Model
{


    public $productId = [];

    private $_discount;



    public function __construct(Discount $discount = null, array $config = [])
    {
       if($discount) {


           $this->productId = ArrayHelper::getColumn(DiscountItem::find()->where(['discount_id' => $discount->id])->asArray()->all(), 'product_id');
           $this->_discount = $discount;
       }

        parent::__construct($config);
    }

    public function rules()
    {
        return [

            ['productId', 'each', 'rule' => ['integer']],



        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'discount_id' => 'Скидка',
            'product_id' => 'Ароматы',
        ];
    }


    public function discountItemList()
    {

        return ArrayHelper::map(Product::find()->asArray()->all(), 'id', 'name');



    }

    public function beforeValidate()
    {
        $this->productId = array_filter((array)$this->productId);

        return parent::beforeValidate();
    }

}