<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 26.05.18
 * Time: 21:31
 */
namespace shop\forms;
use yii\base\Model;

/**
 * Class ContactForm
 * @package shop\forms
 */
class ContactForm extends Model
{


    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;



    public function rules()
    {
        return [
            [['name', 'email', 'subject', 'body'], 'required'],
            ['email', 'email'],
            ['verifyCode', 'captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'=> 'Имя',
            'subject'=>'Тема',
            'body'=>'Сообщение',
            'verifyCode' => 'Код подтверждения',
        ];
    }







}