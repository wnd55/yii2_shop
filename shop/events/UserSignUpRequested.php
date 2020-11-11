<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 07.05.19
 * Time: 13:49
 */

namespace shop\events;

use shop\entities\user\User;

class UserSignUpRequested
{

    public $user;

    public function __construct(User $user)
    {

        $this->user = $user;
    }


}