<?php

namespace shop\helpers;

use shop\entities\User\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class UserHelper
{
    public static function statusList()
    {
        return [
            User::STATUS_DELETED => 'Delete',
            User::STATUS_ACTIVE => 'Active',
        ];
    }

    public static function statusName($status)
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function statusLabel($status)
    {
        switch ($status) {
            case User::STATUS_DELETED:
                $class = 'label label-default';
                break;
            case User::STATUS_ACTIVE:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
        }

        return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => $class,
        ]);
    }
}