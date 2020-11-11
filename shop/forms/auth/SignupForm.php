<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 11.05.18
 * Time: 13:32
 */

namespace shop\forms\auth;


use yii\base\Model;
use shop\entities\user\User;


class SignupForm extends Model
{

    public $username;
    public $email;
    public $phone;
    public $password;
    public $privacy;


    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->phone = '+7';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'Это имя пользователя уже занято.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'Этот адрес электронной почты уже занят.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['phone', 'required'],
            ['phone', 'string'],

            ['privacy', 'required', 'message' => 'Подтвердите'],
            ['privacy', 'boolean', 'trueValue' => true, 'falseValue' => false, 'strict' => false, 'skipOnEmpty' =>true,],
            ['privacy', 'in', 'range' => [1,], 'message' => 'Подтвердите'],
        ];
    }

    public function attributeLabels()
    {

        return [

            'username' => 'Логин',
            'email' => 'Email',
            'password' => 'Пароль',
            'phone' => 'Телефон',
            'privacy' => 'Соглашение обработки персональных данных'

        ];


    }


}