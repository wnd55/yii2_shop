<?php

namespace shop\services\blog;

use shop\entities\blog\post\Post;
use shop\forms\blog\CommentForm;
use shop\repositories\blog\PostRepository;
use shop\repositories\UserRepository;

/**
 * Class CommentService
 * @package shop\services\blog
 */
class CommentService
{
    private $posts;
    private $users;



    public function __construct(PostRepository $posts, UserRepository $users)
    {
        $this->posts = $posts;
        $this->users = $users;

    }


    public function create($postId, $userId, CommentForm $form)
    {
        /**
         * @var $post Post
         */

        $post = $this->posts->get($postId);
        $user = $this->users->get($userId);

        $comment = $post->addComment($post->id, $user->id, $form->parentId, $form->text);

        $this->posts->save($post);

        return $comment;
    }


}