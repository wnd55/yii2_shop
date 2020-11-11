<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 26.05.18
 * Time: 14:11
 */

namespace shop\services\auth;

use shop\forms\auth\LoginForm;
use shop\repositories\UserRepository;

class AuthService
{

    private $users;

    public function __construct(UserRepository $users)
    {

        $this->users = $users;

    }


    public function auth(LoginForm $form)
    {

        $user = $this->users->findByUsernameOrEmail($form->email);

        if(!$user || !$user->isActive() || !$user->validatePassword($form->password)){

            throw new \DomainException('Неизвестный пользователь или пароль.');
        }

        return $user;



    }






}