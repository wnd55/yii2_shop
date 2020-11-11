<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 19.03.18
 * Time: 17:35
 */

namespace shop\forms\manage\shop;

use shop\entities\shop\Category;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class CategoryForm extends Model
{

    public $name;
    public $title;
    public $description;
    public $parentId;
    public $meta_title;
    public $meta_description;
    public $meta_keywords;
    public $status;

    private $_category;


    public function __construct(Category $category = null, array $config = [])
    {
        if ($category) {
            $this->name = $category->name;
            $this->title = $category->title;
            $this->description = $category->description;
            $this->parentId = $category->parent ? $category->parent->id : null;
            $this->meta_title = $category->meta_title;
            $this->meta_description = $category->meta_description;
            $this->meta_keywords = $category->meta_keywords;
            $this->status = $category->status;

            $this->_category = $category;
        }

        parent::__construct($config);
    }

    public function attributeLabels()
    {
        return [


            'status' => 'Статус категории'
        ];
    }

    public function attributeHints()
    {
        return [

            'status' => 'Активна или нет категория'


        ];


    }


    public function rules()
    {
        return [

            [['name', 'meta_title', 'meta_description', 'meta_keywords'], 'required'],
            [['status'], 'boolean'],
            [['parentId'], 'integer'],
            [['name', 'title', 'meta_title', 'meta_description', 'meta_keywords'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['name'], 'unique', 'targetClass' => Category::class,
                'filter' => $this->_category ? ['<>', 'id', $this->_category->id] : null]


        ];


    }

    public function parentCategoriesList()
    {
        return ArrayHelper::map(Category::find()->orderBy('lft')->asArray()->andWhere($this->_category ? ['<>', 'id', $this->_category->id] : null)->all(), 'id', function (array $category) {


            return ($category['depth'] > 1 ? str_repeat('-- ', $category['depth'] - 1) . ' ' : '') . $category['name'];
        }

        );
    }


}