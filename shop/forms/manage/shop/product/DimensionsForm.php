<?php

namespace shop\forms\manage\shop\product;

use shop\entities\shop\product\Product;
use shop\entities\shop\product\ShopProductsDimensions;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class DimensionsForm extends Model
{

    public $dimensions = [];

    public function __construct(Product $product = null, array $config = [])
    {
        if ($product) {

            $this->dimensions = ArrayHelper::getColumn($product->dimensionsAssignments, 'dimension_id');
        }

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            ['dimensions', 'each', 'rule' => ['integer']],

        ];
    }

    public function dimensionsList()
    {
        $productsDimensions = ShopProductsDimensions::find()->orderBy('name')->asArray()->all();
        return ArrayHelper::map($productsDimensions, 'id',
            function ($productsDimensions) {
                return $productsDimensions['name'] . ' ' . $productsDimensions['price'] .' '.'₽';
            });


    }

    public function beforeValidate()
    {
        $this->dimensions = array_filter((array)$this->dimensions);

        return parent::beforeValidate();
    }
}