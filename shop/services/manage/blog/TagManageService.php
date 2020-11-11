<?php

namespace shop\services\manage\blog;

use shop\entities\blog\Tag;
use shop\forms\manage\blog\TagForm;
use shop\repositories\blog\TagRepository;

/**
 * Class TagManageService
 * @package shop\services\manage\blog
 */
class TagManageService
{
    private $tags;

    public function __construct(TagRepository $tags)
    {
        $this->tags = $tags;
    }

    public function create(TagForm $form)
    {
        $tag = Tag::create(
            $form->name

        );
        $this->tags->save($tag);
        return $tag;
    }

    public function edit($id, TagForm $form)
    {
        $tag = $this->tags->get($id);
        $tag->edit(
            $form->name

        );
        $this->tags->save($tag);
    }

    public function remove($id)
    {
        $tag = $this->tags->get($id);
        $this->tags->remove($tag);
    }
}