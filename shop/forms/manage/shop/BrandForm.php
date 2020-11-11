<?php
/**
 * Created by PhpStorm.
 * User: MaximIV
 * Date: 18.03.2018
 * Time: 15:14
 */

namespace shop\forms\manage\shop;

use shop\entities\shop\Brand;
use yii\base\Model;

class BrandForm extends Model
{
    public $name;
    public $meta_title;
    public $meta_description;
    public $meta_keywords;

    private $_brand;


   public function __construct(Brand $brand = null, array $config = [])
   {
       if($brand){

           $this->name = $brand->name;
           $this->meta_title = $brand->meta_title;
           $this->meta_description = $brand->meta_description;
           $this->meta_keywords = $brand->meta_keywords;

           $this->_brand = $brand;
       }


       parent::__construct($config);
   }


    public function rules()
    {
        return [
            [['name', 'meta_title', 'meta_description', 'meta_keywords'], 'string', 'max' => 255],
            [['name', 'meta_title', 'meta_description', 'meta_keywords'], 'required',],
            [['name'], 'unique', 'targetClass' => Brand::class,
                'filter' => $this->_brand ? ['<>', 'id', $this->_brand->id] : null]
        ];
    }


}