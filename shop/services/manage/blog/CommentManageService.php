<?php

namespace shop\services\manage\blog;

use shop\entities\blog\post\Post;
use shop\forms\manage\blog\post\CommentEditForm;
use shop\repositories\blog\PostRepository;

/**
 * Class CommentManageService
 * @package shop\services\manage\blog
 */
class CommentManageService
{
    private $posts;

    public function __construct(PostRepository $posts)
    {
        $this->posts = $posts;
    }

    /**
     * @param $postId
     * @param $id
     * @param CommentEditForm $form
     */
    public function edit($postId, $id, CommentEditForm $form)
    {
        $post = $this->posts->get($postId);
        $post->editComment($id, $form->parentId, $form->text);
        $this->posts->save($post);
    }

    /**Активация комменария и обновление счётчика
     * @param $postId
     * @param $id
     */
    public function activate($postId, $id)
    {
        /**
         * @var  $post Post
         */
        $post = $this->posts->get($postId);
        $post->activateComment($id);
        $post->comments_count += 1;
        $this->posts->save($post);
    }

    /**Выключение счётчика комменария и обновление счётчика
     * @param $postId
     * @param $id
     */
    public function deactivate($postId, $id)
    {
        /**
         * @var  $post Post
         */
        $post = $this->posts->get($postId);
        $post->deactivateComment($id);
        if ($post->comments_count > 0) {
            $post->comments_count -= 1;
            $this->posts->save($post);
        }


    }

    /**
     * @param $postId
     * @param $id
     */
    public function remove($postId, $id)
    {
        $post = $this->posts->get($postId);
        $post->removeComment($id);
        $this->posts->save($post);
    }
}