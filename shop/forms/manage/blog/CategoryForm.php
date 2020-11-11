<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 19.02.18
 * Time: 18:14
 */

namespace shop\forms\manage\blog;

use shop\entities\blog\Category;
use yii\base\Model;

class CategoryForm extends Model
{


    public $name;
    public $title;
    public $description;
    public $sort;
    public $meta_title;
    public $meta_description;
    public $meta_keyword;


    private $_category;

    public function __construct(Category $category = null, array $config = [])
    {
        if ($category) {

            $this->name = $category->name;
            $this->title = $category->title;
            $this->description = $category->description;
            $this->sort = $category->sort;
            $this->meta_title = $category->meta_title;
            $this->meta_description = $category->meta_description;
            $this->meta_keyword = $category->meta_keyword;

            $this->_category = $category;

        } else {
            $this->sort = Category::find()->max('sort') + 1;

        }


        parent::__construct($config);
    }


    public function rules()
    {
        return [
            [['name', 'meta_title', 'meta_description', 'meta_keyword'], 'required'],
            [['name', 'title', 'meta_title', 'meta_description', 'meta_keyword'], 'string', 'max' => 255],
            [['description'], 'string'],

            [['name'], 'unique', 'targetClass' => Category::className(), 'filter' => $this->_category ? ['<>', 'id', $this->_category->id] : null]
        ];
    }


}