<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 26.05.18
 * Time: 16:42
 */

namespace shop\forms\auth;



use shop\entities\user\User;
use yii\base\Model;

class PasswordResetRequestForm extends Model
{

    public $email;


    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => User::class,
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'Пользователь, с таким адресом, не зарегестрирован.'
            ],
        ];
    }



}