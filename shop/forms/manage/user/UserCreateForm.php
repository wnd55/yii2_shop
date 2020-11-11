<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 28.05.18
 * Time: 15:30
 */

namespace shop\forms\manage\user;



use shop\entities\user\User;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class UserCreateForm extends Model
{

    public $username;
    public $email;
    public $phone;
    public $password;
    public $role;


    public function rules()
    {
        return [
            [['username', 'email', 'phone', 'role'], 'required'],
            ['email', 'email'],
            [['username', 'email'], 'string', 'max' => 255],
            [['username', 'email', 'phone'], 'unique', 'targetClass' => User::class],
            ['password', 'string', 'min' => 6],
            ['phone', 'integer'],
        ];
    }



    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Логин',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'phone' => 'Телефон',

        ];
    }





    public function rolesList()
    {
        return ArrayHelper::map(\Yii::$app->authManager->getRoles(), 'name', 'description');
    }

}