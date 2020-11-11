<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 12.04.19
 * Time: 11:08
 */

namespace shop\forms\manage\shop;


use shop\entities\shop\DiscountUser;
use yii\base\Model;

class DiscountUserForm extends Model
{
    public $percent;
    public $name;
    public $status;

    /**
     * DiscountUserForm constructor.
     * @param DiscountUser|null $discountUser
     * @param array $config
     */
    public function __construct( DiscountUser $discountUser = null, array $config = [])
    {
        if($discountUser) {

            $this->percent = $discountUser->percent;
            $this->name = $discountUser->name;
            $this->status = $discountUser->status;

        }


        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Дата создания',
            'percent' => 'Процент скидки',
            'name' => 'Название скидки',
            'status' => 'Статус',
        ];
    }




    public function rules()
    {
        return [

           [['percent', 'name',], 'required'],
           ['percent', 'integer'],
           ['name', 'string', 'max' => 255],
           ['status', 'boolean'],




        ];
    }


}