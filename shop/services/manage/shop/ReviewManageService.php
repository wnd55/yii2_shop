<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 10.05.19
 * Time: 22:18
 */

namespace shop\services\manage\shop;

use backend\widgets\grid\ReviewActivateColumn;
use shop\dispatchers\EventDispatcher;
use shop\entities\shop\product\Product;
use shop\events\UserAddNewReview;
use shop\forms\manage\shop\product\ReviewCreateForm;
use shop\forms\manage\shop\product\ReviewForm;
use shop\repositories\shop\ProductRepository;
use Yii;
use shop\entities\shop\product\Review;
use shop\repositories\shop\ReviewRepository;

class ReviewManageService
{

    public $reviews;
    private $dispatcher;
    private $productRepository;

    public function __construct(ReviewRepository $reviews, EventDispatcher $dispatcher, ProductRepository $productRepository)
    {
        $this->reviews = $reviews;
        $this->dispatcher = $dispatcher;
        $this->productRepository = $productRepository;


    }

    public function create($productId, ReviewForm $reviewForm)
    {

        /**
         * @var $product Product
         */
        $userId = Yii::$app->user->id;

        $review = Review::create(

            $userId,
            $productId,
            (int)$reviewForm->vote,
            $reviewForm->text,
            $reviewForm->name
        );

        $this->reviews->save($review);
        $this->dispatcher->dispatch(new UserAddNewReview($review));

        return $review;
    }


    public function createReview(ReviewCreateForm $createForm)
    {

        $userId = Yii::$app->user->id;

        $review = Review::createReview(

            $userId,
            $createForm->product_id,
            (int)$createForm->vote,
            $createForm->created_at,
            $createForm->text,
            $createForm->name
        );

        $this->reviews->save($review);

        return $review;
    }


    public function activateReview($id)
    {
        /**
         * @var $review Review
         * @var $product Product
         */

        $review = $this->reviews->get($id);
        $review->activate();
        $this->reviews->save($review);
        $product = $this->productRepository->get($review->product_id);
        $product->addRating($product);

    }

    public function deactivationReview($id)
    {
        /**
         * @var $review Review
         *  @var $product Product
         */
        $review = $this->reviews->get($id);
        $review->deactivation();
        $this->reviews->save($review);
        $product = $this->productRepository->get($review->product_id);
        $product->addRating($product);

    }


    public function edit($id, ReviewForm $formEdit)
    {
        /**
         * @var $review Review
         */
        $review = $this->reviews->get($id);
         $review->edit($formEdit->name, $formEdit->text, $formEdit->vote, $formEdit->created_at);
         $this->reviews->save($review);


    }

    public function remove($id)
    {
        $review = $this->reviews->get($id);
        $this->reviews->remove($review);


    }

}