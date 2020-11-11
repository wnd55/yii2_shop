<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 20.02.18
 * Time: 15:47
 */


namespace shop\repositories\blog;

use shop\entities\blog\post\Post;


class PostRepository
{
    public function get($id)
    {
        if (!$post = Post::findOne($id)) {
            throw new \DomainException('post is not found.');
        }
        return $post;
    }

    public function existsByCategory($id)
    {
        return Post::find()->andWhere(['category_id' => $id])->exists();
    }


    public function save(Post $post)
    {
        if (!$post->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }


    public function remove(Post $post)
    {
        if (!$post->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }




}