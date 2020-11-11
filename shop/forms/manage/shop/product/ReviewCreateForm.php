<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 17.05.19
 * Time: 20:06
 */

namespace shop\forms\manage\shop\product;

use yii\base\Model;
use shop\entities\shop\product\Product;
use yii\helpers\ArrayHelper;

class ReviewCreateForm extends Model
{

    public $vote;
    public $text;
    public $name;
    public $created_at;
    public $product_id;



    public function rules()
    {
        return [

            [['vote', 'text', 'name'], 'required'],
            [['vote'], 'in', 'range' => [1, 2, 3, 4, 5]],
            [['text', 'name'], 'string'],
            [['product_id'], 'integer'],
            [['created_at', ], 'date', 'format' => 'php:Y-m-d' ]

        ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Дата создания',
            'user_id' => 'User ID',
            'vote' => 'Голос',
            'text' => 'Отзыв',
            'active' => 'Активность',
            'product_id' => 'Аромат',
            'name' => 'Имя'
        ];
    }


    public function votesList()
    {
        return [
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5,
        ];
    }

    public function productList()
    {
        return ArrayHelper::map(Product::find()->orderBy('name')->asArray()->all(),'id', 'name');

    }


}