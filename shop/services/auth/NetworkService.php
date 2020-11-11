<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 04.01.19
 * Time: 19:31
 */

namespace shop\services\auth;


use shop\access\Rbac;
use shop\entities\user\Network;
use shop\entities\user\User;
use shop\repositories\UserRepository;
use shop\services\RoleManager;

class NetworkService
{

    /**
     * @var UserRepository
     */
    private $users;
    private $roles;

    /**
     * NetworkService constructor.
     * @param UserRepository $users
     * @param RoleManager $roles
     */
    public function __construct(UserRepository $users, RoleManager $roles)
    {
        $this->users = $users;
        $this->roles = $roles;
    }

    /**
     * @param $network
     * @param $identity
     * @return array|null|User|\yii\db\ActiveRecord
     */

    public function auth($network, $identity)
    {
        if ($user = $this->users->findByNetworkIdentity($network, $identity)) {
            return $user;
        }
        $user = User::signupByNetwork();
        $this->users->save($user);
        $user->updateAttributes(['username' => 'facebook_user:identity - ' . $identity]);
         Network::create($network, $identity, $user->id)->save();

        $this->roles->assign($user->id, Rbac::ROLE_USER);

        return $user;
    }


    /**
     * @param $id
     * @param $network
     * @param $identity
     */

    public function attach($id, $network, $identity)
    {
        if ($this->users->findByNetworkIdentity($network, $identity)) {
            throw new \DomainException('Сеть уже зарегистрирована..');
        }
        $user = $this->users->get($id);
        $user->attachNetwork($network, $identity);
//        $this->users->save($user);
    }





}