<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 14.02.19
 * Time: 11:18
 */

namespace shop\services\manage;


use shop\entities\Page;
use shop\forms\manage\PageForm;
use shop\repositories\PageRepository;

class PageManageService
{


    private $pages;

    public function __construct(PageRepository $pages)
    {

        $this->pages = $pages;

    }

    /**
     * @param PageForm $form
     * @return static
     */
    public function create(PageForm $form)
    {
        $parent = $this->pages->get($form->parentId);

        $page = Page::create(

            $form->title,
            $form->content,
            $form->meta_title,
            $form->meta_description,
            $form->meta_keywords

        );

        $page->appendTo($parent);

        $this->pages->save($page);

        return $page;
    }


    public function edit($id, PageForm $form)
    {

        $page = $this->pages->get($id);
        $this->assertIsNotRoot($page);
        $page->edit(

            $form->title,
            $form->content,
            $form->meta_title,
            $form->meta_description,
            $form->meta_keywords,
            $form->slug
        );

        if ($form->parentId !== $page->parent->id) {
            $parent = $this->pages->get($form->parentId);
            $page->appendTo($parent);
        }
        $this->pages->save($page);
    }

    public function moveUp($id)
    {
        $page = $this->pages->get($id);
        $this->assertIsNotRoot($page);
        if ($prev = $page->prev) {
            $page->insertBefore($prev);
        }
        $this->pages->save($page);
    }

    public function moveDown($id)
    {
        $page = $this->pages->get($id);
        $this->assertIsNotRoot($page);
        if ($next = $page->next) {
            $page->insertAfter($next);
        }
        $this->pages->save($page);
    }


    public function remove($id)
    {
        $page = $this->pages->get($id);
        $this->assertIsNotRoot($page);

        $this->pages->remove($page);
    }


    private function assertIsNotRoot(Page $page)
    {
        if ($page->isRoot()) {
            throw new \DomainException('Unable to manage the root category.');
        }
    }

}