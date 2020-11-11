<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 21.02.18
 * Time: 10:38
 */

namespace shop\helpers;


use shop\entities\Blog\Post\Post;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class PostHelper
{

    public static function statusList()
    {
        return[

            Post::STATUS_DRAFT =>'Draft',
            Post::STATUS_ACTIVE => 'Active',
        ];


    }

    public static function statusName($status)
    {

        return ArrayHelper::getValue(self::statusList(), $status);


    }


   public static function statusLabel($status)
   {
      switch($status){

          case Post::STATUS_DRAFT:

              $class = 'label label-default';

              break;

          case Post::STATUS_ACTIVE:

              $class = 'label label-success';

              break;


          default:

              $class = 'label label-default';

      }

       return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), ['class'=>$class]);



   }







}