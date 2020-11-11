<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 26.05.18
 * Time: 16:37
 */

namespace shop\services\auth;


use shop\forms\auth\ResetPasswordForm;
use Yii;
use shop\forms\auth\PasswordResetRequestForm;
use shop\repositories\UserRepository;
use yii\mail\MailerInterface;

class PasswordResetService
{

    private $mailer;
    private $users;

    public function __construct(UserRepository $users, MailerInterface $mailer)
    {

        $this->mailer = $mailer;
        $this->users = $users;

    }


    public function request(PasswordResetRequestForm $form)
    {

        $user = $this->users->getByEmail($form->email);

        if (!$user->isActive()) {
            throw new \DomainException('User is not active.');
        }


        $user->requestPasswordReset();
        $this->users->save($user);

        $sent = $this->mailer
            ->compose(
                ['html' => 'auth/reset/confirm-html', 'text' => 'auth/reset/confirm-text'],
                ['user' => $user]

            )
            ->setTo($user->email)
            ->setSubject('Password reset for ' . Yii::$app->name)
            ->send();

        if (!$sent) {
            throw new \RuntimeException('Sending error.');
        }

    }

    public function validateToken($token)
    {

        if (empty($token) || !is_string($token)) {
            throw new \DomainException('Password reset token cannot be blank.');
        }
        if(!$this->users->existsByPasswordResetToken($token)){

            throw new \DomainException('Wrong password reset token.');
        }


    }


    public function reset($token, ResetPasswordForm $form)
    {
        $user = $this->users->getByPasswordResetToken($token);
        $user->resetPassword($form->password);
        $this->users->save($user);


    }



}