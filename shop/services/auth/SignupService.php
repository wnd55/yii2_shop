<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 11.05.18
 * Time: 14:30
 */

namespace shop\services\auth;


use shop\dispatchers\EventDispatcher;
use shop\events\UserSignUpRequested;
use shop\services\TransactionManager;
use shop\access\Rbac;
use shop\entities\user\User;
use shop\forms\auth\SignupForm as AuthSignupForm;
use shop\repositories\UserRepository;
use shop\services\RoleManager;


class SignupService
{

    private $users;
    private $roles;
    private $transactionManager;
    private $dispatcher;




    public function __construct(UserRepository $users, RoleManager $roles, TransactionManager $transactionManager, EventDispatcher $dispatcher)
    {


        $this->users = $users;
        $this->roles = $roles;
        $this->transactionManager = $transactionManager;
        $this->dispatcher = $dispatcher;



    }



    public function signup(AuthSignupForm $form)
    {

        $user = User::requestSignup(
            $form->username,
            $form->email,
            $form->phone,
            $form->password
        );

        $this->transactionManager->wrap(function () use ($user) {

            $this->users->save($user);
            $this->roles->assign($user->id, Rbac::ROLE_USER);
        }) ;


        $this->dispatcher->dispatch(new UserSignUpRequested($user));

    }


}

