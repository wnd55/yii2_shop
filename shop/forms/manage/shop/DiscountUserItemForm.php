<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 13.04.19
 * Time: 14:57
 */

namespace shop\forms\manage\shop;

use shop\entities\user\User;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class DiscountUserItemForm extends Model
{


    public $userId;

    public function rules()
    {
        return [


            ['userId', 'integer'],
            ['userId', 'required'],

        ];
    }



    public function usersList()
    {
        return ArrayHelper::map(User::find()->where(['status' => User::STATUS_ACTIVE])->asArray()->all(), 'id', 'username');

    }

}