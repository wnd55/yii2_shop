<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 20.02.18
 * Time: 16:04
 */

namespace shop\forms\manage\blog\post;


use shop\entities\blog\Category;
use shop\entities\blog\Post\Post;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class PostForm extends Model
{
    public $userId;
    public $categoryId;
    public $title;
    public $description;
    public $content;
    public $photo;
    public $meta_title;
    public $meta_description;
    public $meta_keyword;

    public function __construct(Post $post = null, array $config = [])
    {


        if ($post) {
            $this->userId = $post->user_id;
            $this->categoryId = $post->category_id;
            $this->title = $post->title;
            $this->description = $post->description;
            $this->content = $post->content;
            $this->meta_title = $post->meta_title;
            $this->meta_description = $post->meta_description;
            $this->meta_keyword = $post->meta_keyword;

        }


        parent::__construct($config);
    }


    public function rules()
    {
        return [
            [['categoryId', 'title'], 'required'],
            [['title', 'meta_title', 'meta_description', 'meta_keyword'], 'string', 'max' => 255],
            [['categoryId'], 'integer'],
            [['description', 'content'], 'string'],
            [['photo'], 'image'],
        ];
    }

    public function categoriesList()
    {
        return ArrayHelper::map(Category::find()->orderBy('sort')->asArray()->all(), 'id', 'name');
    }


}