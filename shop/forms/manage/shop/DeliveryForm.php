<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 28.05.18
 * Time: 12:56
 */

namespace shop\forms\manage\shop;



use shop\entities\shop\Delivery;
use yii\base\Model;

class DeliveryForm extends Model
{


    public $name;
    public $cost;
    public $minWeight;
    public $maxWeight;
    public $sort;

    public function __construct(Delivery $delivery = null, $config = [])
    {

        if ($delivery) {
            $this->name = $delivery->name;
            $this->cost = $delivery->cost;
            $this->minWeight = $delivery->min_weight;
            $this->maxWeight = $delivery->max_weight;
            $this->sort = $delivery->sort;
        } else {
    $this->sort = Delivery::find()->max('sort') + 1;
    }


        parent::__construct($config);
    }


    public function rules()
    {
        return [
            [['name', 'cost', 'sort'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['cost', 'minWeight', 'maxWeight', 'sort'], 'integer'],
        ];
    }




}
