<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 12.04.19
 * Time: 12:51
 */

namespace shop\services\manage\shop;

use shop\entities\shop\DiscountUser;
use shop\entities\shop\DiscountUserItem;
use shop\forms\manage\shop\DiscountUserForm;
use shop\forms\manage\shop\DiscountUserItemForm;
use shop\repositories\shop\DiscountUserItemRepository;
use shop\repositories\shop\DiscountUserRepository;


class DiscountUserManageService
{
    public $discountsUsers;
    public $discountsUsersItems;

    public function __construct(DiscountUserRepository $discountUser, DiscountUserItemRepository $discountsUsersItems)
    {

        $this->discountsUsers = $discountUser;
        $this->discountsUsersItems = $discountsUsersItems;
    }


    public function create(DiscountUserForm $form)
    {

        $discountUser = DiscountUser::create(

            $form->name,
            $form->percent,
            $form->status

        );

        $this->discountsUsers->save($discountUser);

        return $discountUser;


    }


    public function edit($id, DiscountUserForm $form)
    {

        $discountUser = $this->discountsUsers->get($id);

        $discountUser->edit(

            $form->name,
            $form->percent,
            $form->status
        );

        if($form->status == false && $this->discountsUsersItems->existsByDiscountUserItem($id)) {

            throw new \DomainException('Невозможно поменять стаус, так как существует связь с пользователем');

        }

        $this->discountsUsers->save($discountUser);


    }


    public function remove($id)
    {
        $discountUser = $this->discountsUsers->get($id);

        if ($this->discountsUsersItems->existsByDiscountUserItem($id)) {

            throw new \DomainException('Невозможно удалить скидку, так как существует связь с пользователем');
        }

        $this->discountsUsers->remove($discountUser);

    }



    public function addUser($id, $userId)
    {

        $discountUser = $this->discountsUsers->get($id);


        if ($this->discountsUsersItems->existsUserDiscountUserItem($userId)) {

            $this->discountsUsersItems->removeUserDiscount($userId);

        }

        $discountUserItem = DiscountUserItem::create($discountUser->id, $userId);

        $this->discountsUsersItems->save($discountUserItem);

        return $discountUserItem;
    }



    public function removeDiscountUser($userId)
    {
        if ($this->discountsUsersItems->existsUserDiscountUserItem($userId)) {

            $this->discountsUsersItems->removeUserDiscount($userId);

        }


    }


}