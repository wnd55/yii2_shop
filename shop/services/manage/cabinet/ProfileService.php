<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 06.06.18
 * Time: 14:35
 */
namespace shop\services\manage\cabinet;


use shop\forms\user\ProfileEditForm;
use shop\repositories\UserRepository;

class ProfileService

{
    private $users;


    public function __construct(UserRepository $users)
    {

        $this->users = $users;

    }


    public function edit($id, ProfileEditForm $form)
    {

        $user = $this->users->get($id);
        $user->editProfile($form->email, $form->phone);
        $this->users->save($user);



    }




}