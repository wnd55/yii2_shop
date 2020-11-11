<?php

namespace shop\helpers;

use yii\helpers\Html;
use Yii;

class PriceHelper
{
    public static function format($price)
    {
        return number_format($price, 0, '.', ' '.'').Yii::$app->formatter->asHtml(' <i class="fa fa-ruble-sign" aria-hidden="true"></i>');
    }

    public static function ruble()
    {

        return Yii::$app->formatter->asHtml(' <i class="fa fa-ruble-sign" aria-hidden="true"></i>');

    }
} 