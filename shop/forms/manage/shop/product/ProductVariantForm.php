<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 04.04.19
 * Time: 23:52
 */

namespace shop\forms\manage\shop\product;


use shop\entities\shop\product\ProductVariant;
use yii\base\Model;

class ProductVariantForm extends Model
{

    public $name;
    public $size;
    public $price_new;
    public $price_old;

    private $_productVariant;

    /**
     * ProductVariantForm constructor.
     * @param ProductVariant|null $productVariant
     * @param array $config
     */
    public function __construct(ProductVariant $productVariant = null, array $config = [])
    {

        if ($productVariant) {
            $this->name = $productVariant->name;
            $this->size = $productVariant->size;
            $this->price_new = $productVariant->price_new;
            $this->price_old = $productVariant->price_old;

            $this->_productVariant = $productVariant;
        }


        parent::__construct($config);
    }

    /**
     *
     */
    public function rules()
    {
       return [

            [['name', 'size'], 'string', 'max' => 255],
            [['name', 'size'], 'required',],
            [['name'], 'unique', 'targetClass' => ProductVariant::class, 'filter' => $this->_productVariant ? ['<>', 'id', $this->_productVariant->id] : null],
            [['price_new', 'price_old'], 'integer', 'min' => 0],


        ];
    }

}