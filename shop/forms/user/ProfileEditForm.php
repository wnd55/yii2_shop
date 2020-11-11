<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 06.06.18
 * Time: 14:39
 */

namespace shop\forms\user;



use shop\entities\user\User;
use yii\base\Model;

class ProfileEditForm extends Model
{

    public $phone;
    public $email;

    protected $_user;


    public function __construct(User $user, $config = [])
    {

        $this->phone = $user->phone;
        $this->email = $user->email;
        $this->_user = $user;

        parent::__construct($config);
    }


    public function rules()
    {
        return [
            [['phone', 'email'], 'required'],
            ['email', 'email'],
            [['email'], 'string', 'max' => 255],
            [['phone'], 'string'],
            [['phone', 'email'], 'unique', 'targetClass' => User::class, 'filter' => ['<>', 'id', $this->_user->id]],
        ];
    }



}