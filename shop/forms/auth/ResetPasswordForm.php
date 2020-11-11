<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 26.05.18
 * Time: 17:40
 */

namespace shop\forms\auth;

use yii\base\Model;

class ResetPasswordForm extends Model
{
    public $password;

    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }
}

