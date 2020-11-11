<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 09.02.19
 * Time: 13:44
 */

namespace shop\entities\shop;

use shop\entities\shop\queries\DeliveryQuery;
use yii\db\ActiveRecord;



/**
 * @property int $id
 * @property string $name
 * @property int $cost
 * @property int $min_weight
 * @property int $max_weight
 * @property int $sort
 */

class Delivery extends ActiveRecord
{



    public static function tableName()
    {
        return '{{%shop_delivery_methods}}';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'cost' => 'Стоисость',
            'min_weight' => 'Min вес',
            'max_weight' => 'Max вес',
            'sort' => 'Сортировка',
        ];
    }


    public static function find()
    {
        return new DeliveryQuery(static::class);
    }


    public static function create($name, $cost, $minWeight, $maxWeight, $sort)
    {
        $method = new static();
        $method->name = $name;
        $method->cost = $cost;
        $method->min_weight = $minWeight;
        $method->max_weight = $maxWeight;
        $method->sort = $sort;
        return $method;
    }

    public function edit($name, $cost, $minWeight, $maxWeight, $sort)
    {
        $this->name = $name;
        $this->cost = $cost;
        $this->min_weight = $minWeight;
        $this->max_weight = $maxWeight;
        $this->sort = $sort;
    }

    public function isAvailableForWeight($weight)
    {
        return (!$this->min_weight || $this->min_weight <= $weight) && (!$this->max_weight || $weight <= $this->max_weight);
    }





}