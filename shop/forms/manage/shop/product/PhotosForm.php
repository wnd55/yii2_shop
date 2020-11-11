<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 22.03.18
 * Time: 19:51
 */

namespace shop\forms\manage\shop\product;


use yii\base\Model;

class PhotosForm extends Model
{


    public $files;


    public function rules()
    {
        return [
            ['files', 'each', 'rule' => ['image']],
        ];
    }










}