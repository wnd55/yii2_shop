<?php

namespace shop\repositories\blog;

use shop\entities\blog\Tag;


/**
 * Class TagRepository
 * @package shop\repositories\blog
 */
class TagRepository
{
    public function get($id)
    {
        if (!$tag = Tag::findOne($id)) {
            throw new \DomainException('Tag is not found.');
        }
        return $tag;
    }

    public function findByName($name)
    {
        return Tag::findOne(['name' => $name]);
    }

    public function save(Tag $tag)
    {
        if (!$tag->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    public function remove(Tag $tag)
    {
        if (!$tag->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }
}