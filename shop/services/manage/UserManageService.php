<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 11.05.18
 * Time: 17:44
 */

namespace shop\services\manage;


use shop\entities\user\User;
use shop\forms\manage\user\UserCreateForm;
use shop\forms\manage\user\UserEditForm;
use shop\repositories\UserRepository;
use shop\services\RoleManager;
use shop\services\TransactionManager;

class UserManageService
{

    private $repository;
    private $roles;
    private $transaction;


    public function __construct(UserRepository $repository, RoleManager $roles, TransactionManager $transaction)
    {

        $this->repository = $repository;
        $this->roles = $roles;
        $this->transaction = $transaction;

    }


    /**
     * @param UserCreateForm $form
     * @return User
     * @throws \Exception
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */

    public function create(UserCreateForm $form)
    {

        $user = User::create(
            $form->username,
            $form->email,
            $form->phone,
            $form->password
        );

        $this->transaction->wrap(function () use ($user, $form) {

            $this->repository->save($user);
            $this->roles->assign($user->id, $form->role);


        });

        return $user;

    }


    /**
     * @param $id
     * @param UserEditForm $form
     * @throws \Exception
     * @throws \yii\db\Exception
     */


    public function edit($id, UserEditForm $form)
    {

        $user = $this->repository->get($id);

        $user->edit(
            $form->username,
            $form->email,
            $form->phone
        );

        $this->transaction->wrap(function () use ($user, $form) {

            $this->repository->save($user);
            $this->roles->assign($user->id, $form->role);


        });

    }

    /**
     * @param $id
     * @param $role
     */

    public function assignRole($id, $role)
    {
        $user = $this->repository->get($id);
        $this->roles->assign($user->id, $role);
    }

    /**
     * @param $id
     */

    public function remove($id)
    {
        $user = $this->repository->get($id);
        $this->repository->remove($user);

    }


}