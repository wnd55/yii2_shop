<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 14.02.19
 * Time: 11:54
 */

namespace shop\forms\manage;

use shop\entities\Page;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class PageForm
 * @package shop\forms\manage
 */
class PageForm extends Model
{



    public $title;
    public $content;
    public $parentId;
    public $meta_title;
    public $meta_description;
    public $meta_keywords;
    public $slug;

    private $_page;


    public function __construct(Page $page = null, array $config = [])
    {
        if ($page) {

            $this->title = $page->title;
            $this->content = $page->content;
            $this->parentId = $page->parent ? $page->parent->id : null;
            $this->meta_title = $page->meta_title;
            $this->meta_description = $page->meta_description;
            $this->meta_keywords = $page->meta_keywords;
            $this->slug = $page->slug;

            $this->_page = $page;
        }

        parent::__construct($config);
    }




    public function scenarios()
    {

        $scenarios = parent::scenarios();

        $scenarios['edit'] = ['slug', 'title', 'meta_title', 'meta_description', 'meta_keywords','content'];

        return $scenarios;
    }


    /**
     * @return array
     */
    public function rules()
    {
        return [

            [['title', 'meta_title', 'meta_description', 'meta_keywords', ], 'required'],
            [['title', 'meta_title', 'meta_description', 'meta_keywords', 'slug', ], 'required', 'on' => 'edit'],
            [[ 'slug',  'title', 'meta_title', 'meta_description', 'meta_keywords',], 'string', 'max' => 255, 'on' => 'edit'],
            [['parentId'], 'integer'],
            [[ 'title', 'meta_title', 'meta_description', 'meta_keywords', ], 'string', 'max' => 255],
            [['content'], 'string'],
            [['content'], 'string', 'on' => 'edit'],
            [['title'], 'unique', 'targetClass' => Page::class,
                'filter' => $this->_page ? ['<>', 'id', $this->_page->id] : null],
            [['title'], 'unique', 'targetClass' => Page::class,
                'filter' => $this->_page ? ['<>', 'id', $this->_page->id] : null, 'on' => 'edit'],

            [['slug'], 'unique', 'targetClass' => Page::class,
                'filter' => $this->_page ? ['<>', 'id', $this->_page->id] : null, 'on' => 'edit']


        ];


    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'slug' => 'Slug',
            'content' => 'Текст',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'depth' => 'Depth',
            'parentId'=> 'Главная страница'
        ];
    }






    /**
     * @return array
     */
    public function parentCategoriesList()
    {
        return ArrayHelper::map(Page::find()->orderBy('lft')->asArray()->andWhere($this->_page ? ['<>', 'id', $this->_page->id] : null)->all(), 'id', function (array $page) {


            return ($page['depth'] > 1 ? str_repeat('-- ', $page['depth'] - 1) . ' ' : '') . $page['title'];}

        );
    }











}