<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 20.02.18
 * Time: 15:45
 */

namespace shop\services\manage\blog;

use shop\entities\blog\post\Post;
use shop\entities\blog\post\TagAssignment;
use shop\entities\blog\Tag;
use shop\forms\manage\blog\post\PostForm;
use shop\forms\manage\blog\post\TagsForm;
use shop\repositories\blog\CategoryRepository;
use shop\repositories\blog\PostRepository;
use shop\repositories\blog\TagRepository;
use yii\web\UploadedFile;


/**
 * Class PostManageService
 * @package shop\services\manage\blog
 */
class PostManageService
{
    private $posts;
    private $categories;
    private $tags;

    public function __construct(
        PostRepository $posts,
        CategoryRepository $categories,
        TagRepository $tags

    )
    {
        $this->posts = $posts;
        $this->categories = $categories;
        $this->tags = $tags;


    }



    public function create(PostForm $form, TagsForm $modelTagsForm)
    {
        $category = $this->categories->get($form->categoryId);


        $post = Post::create(
            $category->id,
            $form->title,
            $form->description,
            $form->content,
            $form->meta_title,
            $form->meta_description,
            $form->meta_keyword

        );



        $form->photo = UploadedFile::getInstance($form, 'photo');

        if ($form->photo) {
            $post->upload($form);
        }


        $this->posts->save($post);

        $postId = $post->id;

        foreach ($modelTagsForm->existing as $tagId) {

            TagAssignment::create($postId, $tagId);

        }


        //Новый tag

        if(!empty($modelTagsForm->textNew)) {


            if (!$tag = $this->tags->findByName($modelTagsForm->textNew)) {

                $tag = Tag::create($modelTagsForm->textNew);
                $this->tags->save($tag);
                TagAssignment::create($postId, $tag->id);

            }

        }




        return $post;

    }

    public function edit($id, PostForm $form, TagsForm $modelTagsForm)
    {
        $post = $this->posts->get($id);

        $post->unlink('tagAssignments', $post, $delete = true);

        $category = $this->categories->get($form->categoryId);

        $post->edit(
            $category->id,
            $form->title,
            $form->description,
            $form->content,
            $form->meta_title,
            $form->meta_description,
            $form->meta_keyword

        );



        $form->photo = UploadedFile::getInstance($form, 'photo');

        if ($form->photo) {

            unlink((\Yii::getAlias('@webroot/uploads/' . $post->photo)));

            unlink((\Yii::getAlias('@webroot/uploads/tumb/' . $post->photo)));

            $post->upload($form);
        }
        

        $this->posts->save($post);

        $postId = $post->id;

        foreach ($modelTagsForm->existing as $tagId) {

            TagAssignment::create($postId, $tagId);

        }


        //Новый tag

        if(!empty($modelTagsForm->textNew)) {


            if (!$tag = $this->tags->findByName($modelTagsForm->textNew)) {

                $tag = Tag::create($modelTagsForm->textNew);
                $this->tags->save($tag);
                TagAssignment::create($postId, $tag->id);

            }

        }




    }



    public function activate($id)
    {

        $post = $this->posts->get($id);
        $post->activate();
        $this->posts->save($post);


    }

    public function draft($id)
    {
        $post = $this->posts->get($id);
        $post->draft();
        $this->posts->save($post);
    }


    public function remove($id)
    {
        $post = $this->posts->get($id);

        if ($post->photo) {

            unlink((\Yii::getAlias('@webroot/uploads/' . $post->photo)));

            unlink((\Yii::getAlias('@webroot/uploads/tumb/' . $post->photo)));
      }



        $this->posts->remove($post);
    }

}