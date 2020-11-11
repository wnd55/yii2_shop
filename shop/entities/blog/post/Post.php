<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 20.02.18
 * Time: 14:33
 */

namespace shop\entities\blog\post;

use shop\entities\blog\post\queries\PostQuery;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\imagine\Image;
use shop\entities\blog\Category;
use shop\entities\blog\Tag;
use yii\db\ActiveRecord;
use yii\mail\MailerInterface;
use yii\swiftmailer\Mailer;
use yii\web\UploadedFile;


/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $category_id
 * @property integer $created_at
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $photo
 * @property integer $status
 * @property integer $comments_count
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keyword
 * @property string $slug
 * @property Category $category
 * @property TagAssignment[] $tagAssignments
 * @property Tag[] $tags
 * @property Comment[] $comments
 *
 */
class Post extends ActiveRecord
{
    private $mailer;
    private $adminEmail;

    const STATUS_DRAFT = 0;
    const STATUS_ACTIVE = 1;

//     public $comments;


    public static function tableName()
    {
        return '{{%blog_posts}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [


            'slug' => [
                'class' => SluggableBehavior::class,
                'attribute' => 'title',
                'ensureUnique' => true,
                'immutable' => false

            ]

        ];
    }


    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'category_id' => 'Категория',
            'created_at' => 'Создан',
            'title' => 'Заголовок',
            'description' => 'Описание',
            'content' => 'Содержание',
            'photo' => 'Фото',
            'status' => 'Статус',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'meta_keyword' => 'Meta Keyword',
            'comments_count' => 'Колличество комментариев',
            'slug' => 'Слаг',
        ];
    }


    /**
     * @param $categoryId
     * @param $title
     * @param $description
     * @param $content
     * @param $meta_title
     * @param $meta_description
     * @param $meta_keyword
     * @return static
     */
    public static function create($categoryId, $title, $description, $content, $meta_title, $meta_description, $meta_keyword)
    {

        $post = new static();

        $post->category_id = $categoryId;
        $post->created_at = time();
        $post->status = self::STATUS_DRAFT;
        $post->title = $title;
        $post->description = $description;
        $post->content = $content;
        $post->meta_title = $meta_title;
        $post->meta_description = $meta_description;
        $post->meta_keyword = $meta_keyword;
        $post->comments_count = 0;

        return $post;

    }


    /**
     * @param $categoryId
     * @param $title
     * @param $description
     * @param $content
     * @param $meta_title
     * @param $meta_description
     * @param $meta_keyword
     */
    public function edit($categoryId, $title, $description, $content, $meta_title, $meta_description, $meta_keyword)
    {


        $this->category_id = $categoryId;
        $this->title = $title;
        $this->description = $description;
        $this->content = $content;
        $this->meta_title = $meta_title;
        $this->meta_description = $meta_description;
        $this->meta_keyword = $meta_keyword;
    }


    //Загрузка и валидация картинки

    /**
     * @param $form
     * @return string
     */
    public function upload($form)
    {


        $form->photo = UploadedFile::getInstance($form, 'photo');

        // Генерация random названия картиники
        $randomName = $form->title . Yii::$app->getSecurity()->generateRandomString(9);

        $form->photo->saveAs('uploads/' . $randomName . '.' . $form->photo->extension);

        $newPictureRandomName = $randomName . '.' . $form->photo->extension;

        $image = Image::getImagine();


        $newImage = $image->open(Yii::getAlias('@webroot/uploads/' . $newPictureRandomName));

        $size = $newImage->getSize();
        $ratio = $size->getWidth() / $size->getHeight();

        $width = 125;
        $height = round($width / $ratio);

        $width2 = 600;
        $height2 = round($width2 / $ratio);

        Image::thumbnail('@webroot/uploads/' . $newPictureRandomName, $width, $height)
            ->save(Yii::getAlias('@webroot/uploads/tumb/' . $newPictureRandomName), ['quality' => 90]);

        Image::thumbnail('@webroot/uploads/' . $newPictureRandomName, $width2, $height2)
            ->save(Yii::getAlias('@webroot/uploads/' . $newPictureRandomName), ['quality' => 90]);


        return $this->photo = $newPictureRandomName;


    }

    /**
     * @inheritdoc
     */

    public function unlinkPictures($model)
    {

        unlink((\Yii::getAlias('@webroot/uploads/' . $model->image)));
        unlink((\Yii::getAlias('@webroot/uploads/tumb/' . $model->image)));


    }

    /**
     * @return int
     */
    public function activate()
    {

        if ($this->isActive()) {
            throw new \DomainException('post is already active.');
        }

        return $this->status = self::STATUS_ACTIVE;

    }

    /**
     *
     */
    public function draft()
    {
        if ($this->isDraft()) {
            throw new \DomainException('post is already draft.');
        }
        $this->status = self::STATUS_DRAFT;
    }


    public function isActive()
    {

        return $this->status == self::STATUS_ACTIVE;

    }


    public function isDraft()
    {
        return $this->status == self::STATUS_DRAFT;
    }


    /**
     * @inheritdoc
     * @property integer $id
     */

    /**
     *
     */
    public function afterValidate()
    {
        $this->user_id = \Yii::$app->user->identity->id;
    }

    /**
     * @return PostQuery
     */
    public static function find()
    {
        return new PostQuery(static::className());

    }


    /**
     * @return string
     */
    public function getSeoTitle()
    {
        return $this->meta_title ?: $this->title;
    }


    /** Добавление комментариев
     * @param $postId
     * @param $userId
     * @param $parentId
     * @param $text
     * @return static
     */
    public function addComment($postId, $userId, $parentId, $text)

    {
        $parent = $parentId ? $this->getComment($parentId) : null;

        if ($parent && !$parent->isActive()) {

            throw new \DomainException('Не удается добавить комментарий к неактивному родителю.');
        }

        $comment = Comment::create($postId, $userId, $parent ? $parent->id : null, $text);

        $this->adminEmail = Yii::$app->params['adminEmail'];
        $this->mailer = new Mailer();

        $sent = $this->mailer->compose()
            ->setTo($this->adminEmail)
            ->setFrom($this->adminEmail)
            ->setSubject('Новый комментарий на сайте ' . Yii::$app->name)
            ->setTextBody($text)
            ->send();

        if (!$sent) {
            throw new \RuntimeException('Ошибка отправки.');
        }

        return $comment;


    }

    /**
     * @param $id
     * @return \shop\entities\Blog\Post\Comment
     */
    public function getComment($id)
    {
        foreach ($this->comments as $comment) {
            if ($comment->isIdEqualTo($id)) {
                return $comment;
            }
        }
        throw new \DomainException('Комментарий не найден.');
    }

    /**
     * @param $id
     * @return bool
     */
    private function hasChildren($id)
    {
        foreach ($this->comments as $comment) {
            if ($comment->isChildOf($id)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $comments
     */
    private function updateComments($comments)
    {
        $this->comments_count = count(array_filter($comments, function (Comment $comment) {
            return $comment->isActive();

        }));

    }

    /**Активация комментария
     * @param $id
     */
    public function activateComment($id)
    {
        if (!$comment = Comment::findOne($id)) {
            throw new \DomainException('Комментарий не найден.');
        }
        $comment->active = self::STATUS_ACTIVE;

        if (!$comment->save()) {
            throw new \RuntimeException('Ошибка сохранения.');
        }


    }

    /**Выключение комментария
     * @param $id
     */
    public function deactivateComment($id)
    {
        if (!$comment = Comment::findOne($id)) {
            throw new \DomainException('Комментарий не найден.');
        }
        $comment->active = self::STATUS_DRAFT;

        if (!$comment->save()) {
            throw new \RuntimeException('Ошибка сохранения.');
        }


    }


    //Связи

    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function getTagAssignments()
    {
        return $this->hasMany(TagAssignment::class, ['post_id' => 'id']);
    }

    public function getTags()
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])->via('tagAssignments');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::class, ['post_id' => 'id']);
    }


}