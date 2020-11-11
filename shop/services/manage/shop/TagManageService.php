<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 19.03.18
 * Time: 14:10
 */
namespace shop\services\manage\shop;


use shop\entities\shop\Tag;
use shop\forms\manage\shop\TagForm;
use shop\repositories\shop\TagRepository;


class TagManageService
{
    private $tags;

    public function __construct(TagRepository $tags)
    {
        $this->tags = $tags;


    }

    public function create(TagForm $form)
    {
        $tag = Tag::create($form->name);

        $this->tags->save($tag);

        return $tag;


    }


    public function edit($id, TagForm $form)
    {
        $tag = $this->tags->get($id);

        $tag->edit($form->name);

        $this->tags->save($tag);

    }

    public function remove($id)
    {
        $tag = $this->tags->get($id);

        $this->tags->remove($tag);

    }



}