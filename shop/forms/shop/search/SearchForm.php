<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 26.01.19
 * Time: 18:47
 */

namespace shop\forms\shop\search;


use shop\entities\shop\Brand;
use shop\entities\shop\Category;
use shop\entities\shop\Characteristic;
use shop\entities\shop\product\ValueAssignment;
use shop\forms\CompositeForm;
use yii\base\Model;
use yii\helpers\ArrayHelper;



/**
 * @property ValueSearchForm[] $values
 */

class SearchForm extends Model
{

    public $text;
    public $category;
    public $brand;
    public $values;


    public function __construct(array $config = [])
    {

        parent::__construct($config);
    }


    public function rules()
    {
        return [

            [['text',], 'string'],
            [['brand', 'category'], 'integer'],


        ];
    }


    /**
     * @return array
     */

    public function brandsList()
    {

        return ArrayHelper::map(Brand::find()->orderBy('name')->asArray()->all(), 'id', 'name');


    }

    /**
     * @return array
     */

    public function valueList()
    {

        return ArrayHelper::map(ValueAssignment::find()->orderBy('value')->asArray()->all(), 'product_id', 'value');


    }

    /**
     * @return array
     */
    public function categoriesList()
    {

        return ArrayHelper::map(
            Category::find()
                ->andWhere(['>', 'depth', 0])
                ->orderBy('lft')
                ->asArray()
                ->all(),
            'id', function ($category) {
            return ($category['depth'] > 1 ? str_repeat('--', $category['depth'] - 1) . '' : '') . $category['name'];
        });


    }



}