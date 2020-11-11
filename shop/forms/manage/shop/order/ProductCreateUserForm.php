<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 29.05.19
 * Time: 23:32
 */

namespace shop\forms\manage\shop\order;


use shop\entities\shop\product\Product;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class ProductCreateUserForm extends Model
{

    public $quantity;
    public $product;


    public function rules()
    {
        return [

            [['product',], 'integer'],
            [['quantity'], 'integer', 'min' => 1],

        ];
    }

    public function productOrderList()
    {
        $products = Product::find()->where(['status' => Product::STATUS_ACTIVE])->all();

        return ArrayHelper::map($products, 'id', 'name');

    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'product' => 'Аромат',
            'quantity' => 'Количество',

        ];
    }


}