<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 22.03.18
 * Time: 18:45
 */

namespace shop\forms\manage\shop\product;

use shop\entities\shop\product\Product;
use yii\base\Model;

class QuantityForm extends Model
{


    public $quantity;


    public function __construct(Product $product, array $config = [])
    {
       if($product){

           $this->quantity = $product->quantity;

       }

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['quantity'], 'integer', 'min' => 0],
        ];
    }

}