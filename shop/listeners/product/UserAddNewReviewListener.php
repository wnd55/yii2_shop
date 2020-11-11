<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 14.05.19
 * Time: 11:32
 */

namespace shop\listeners\product;

use Yii;
use shop\events\UserAddNewReview;
use yii\mail\MailerInterface;

class UserAddNewReviewListener
{


    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }


    public function handler(UserAddNewReview $event)
    {


        $sent = $this->mailer
            ->compose(
                ['text' => 'product/review/reviewEmailAdminNotification-text'],
                ['review' => $event->review]

            )
            ->setTo(Yii::$app->params['adminEmail'])
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setSubject('Новый отзыв на сайте ' . Yii::$app->name)
            ->send();


        if (!$sent) {
            throw new \RuntimeException('Sending error.');
        }


    }



}