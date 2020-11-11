<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 07.05.19
 * Time: 14:38
 */


namespace shop\listeners\user;

use Yii;
use shop\events\UserSignUpRequested;
use yii\mail\MailerInterface;

class UserSignupRequestedListener
{


    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }


    public function handler(UserSignUpRequested $event)
    {


        $sent = $this->mailer
            ->compose(
                ['text' => 'auth/signup/emailNotification-text'],
                ['user' => $event->user]

            )
            ->setTo($event->user->email)
            ->setSubject('Регистрация на сайте ' . Yii::$app->name)
            ->send();


        if (!$sent) {
            throw new \RuntimeException('Sending error.');
        }


    }

}