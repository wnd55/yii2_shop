<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 10.05.19
 * Time: 21:50
 */

namespace shop\forms\manage\shop\product;

use shop\entities\shop\product\Product;
use shop\entities\shop\product\Review;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class ReviewForm extends Model
{
    public $vote;
    public $text;
    public $name;
    public $created_at;



    public function __construct(Review $review = null, array $config = [])
    {
        if($review) {

            $this->vote = $review->vote;
            $this->text = $review->text;
            $this->name = $review->name;
            $this->created_at = $review->created_at;
        }

        parent::__construct($config);
    }


    public function rules()
    {
        return [

            [['vote', 'text', 'name'], 'required'],
            [['vote'], 'in', 'range' => [1, 2, 3, 4, 5]],
            [['text', 'name'], 'string'],
            [['created_at',], 'date', 'format' => 'php:Y-m-d'  ]

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

}