<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 26.08.19
 * Time: 12:35
 */

namespace shop\forms;

use yii\base\Model;

class ChatForm extends Model
{

    public $name;
    public $text;


    public function rules()

    {

        return [


            [['name', 'text',], 'string', 'max' => 255],
            [['name',], 'required'],
        ];


    }

    public function attributeLabels()
    {

        return [


            'name' => 'Ваше имя',
            'text' => 'Сообщение'

        ];



    }

}