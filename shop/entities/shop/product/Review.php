<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 08.05.19
 * Time: 22:54
 */

namespace shop\entities\shop\product;


use shop\entities\user\User;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $created_at
 * @property int $user_id
 * @property int $product_id
 * @property int $vote
 * @property string $text
 * @property bool $active
 * @property string $name
 * @property User $userReview
 * @property Product $productReview
 */
class Review extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%shop_reviews}}';
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Дата создания',
            'user_id' => 'Клиент',
            'vote' => 'Голос',
            'text' => 'Отзыв',
            'active' => 'Активность',
            'product_id' => 'Аромат',
            'name' => 'Имя'
        ];
    }

    public static function create($userId, $productId, $vote, $text, $name)
    {
        $review = new static();
        $review->user_id = $userId;
        $review->product_id = $productId;
        $review->vote = $vote;
        $review->text = $text;
        $review->created_at = time();
        $review->active = false;
        $review->name = $name;

        return $review;


    }

    public static function createReview($userId, $productId, $vote,  $created_at, $text, $name)
    {
        $review = new static();
        $review->user_id = $userId;
        $review->product_id = $productId;
        $review->vote = $vote;
        $review->created_at = strtotime($created_at);
        $review->text = $text;
        $review->name = $name;
        $review->active = false;

        return $review;


    }




    public function edit($name, $text, $vote, $created_at)
    {
        $this->name = $name;
        $this->text = $text;
        $this->vote = $vote;
        $this->created_at = strtotime($created_at);

    }


    public function activate()
    {
        $this->active = true;

    }

    public function deactivation()
    {
        $this->active = false;

    }

    public function isActive()
    {
        return $this->active == true;
    }


    public static function activeList()
    {
        return [

            0 => 'Неактивный',
            1 => 'Активный'
        ];


    }

    public function getUserReview()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);

    }

    public function getProductReview()
    {

        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

}