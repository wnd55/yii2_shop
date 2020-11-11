<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 17.04.19
 * Time: 12:19
 */
namespace shop\forms\manage\shop;


use shop\entities\shop\Discount;
use yii\base\Model;




class DiscountForm extends Model
{


    public $name;
    public $percent;
    public $from_date;
    public $to_date;
    public $active;
    public $sort;




    public function __construct(Discount $discount = null, array $config = [])
    {
        if($discount) {

            $this->name = $discount->name;
            $this->percent = $discount->percent;
            $this->from_date = $discount->from_date;
            $this->to_date = $discount->to_date;
            $this->active = $discount->active;
            $this->sort = $discount->sort;

        }

        else {
            $this->sort = Discount::find()->max('sort') + 1;
        }
        parent::__construct($config);
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

    public function rules()
    {
        return [

            [['percent', 'name',], 'required'],
            [['percent','sort'], 'integer'],
            ['name', 'string', 'max' => 255],
            ['active', 'boolean'],
            [['from_date', 'to_date'], 'date', 'format' => 'yyyy-MM-dd']


        ];
    }


}