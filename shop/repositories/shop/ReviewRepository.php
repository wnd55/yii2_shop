<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 10.05.19
 * Time: 22:11
 */

namespace shop\repositories\shop;

use shop\entities\shop\product\Review;
use yii\web\NotFoundHttpException;

class ReviewRepository
{

    public function get($id)
    {
        if(!$review = Review::findOne($id)) {

            throw new NotFoundHttpException('Отзыв не найден.');
        }

        return $review;
    }

    public function save(Review $review)
    {

        if(!$review->save()) {

            throw new \RuntimeException('Ошибка сохранения.');
        }
    }


    public function remove(Review $review)
    {

        if(!$review->delete()) {

            throw new \RuntimeException('Ошибка удаления.');
        }
    }



}