<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 14.05.19
 * Time: 11:23
 */
namespace shop\events;

use shop\entities\shop\product\Review;

class UserAddNewReview
{

    public $review;

    public function __construct(Review $review)
    {
        $this->review = $review;
    }


}