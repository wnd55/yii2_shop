<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 06.04.18
 * Time: 21:44
 */

namespace shop\forms\shop;


use shop\entities\shop\product\Product;
use yii\base\Model;


class AddToCartForm extends Model
{


    public $quantity;
    private $_product;

    public function __construct(Product $product,   $config = [])
    {
        $this->_product = $product;
        $this->quantity = 1;



        parent::__construct($config);
    }


    public function rules()
    {

        return [

            ['quantity', 'integer', 'max' => $this->_product->quantity],



        ];

    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'quantity' => 'Количество',

        ];
    }






}
