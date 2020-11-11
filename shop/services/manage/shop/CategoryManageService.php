<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 19.03.18
 * Time: 18:37
 */

namespace shop\services\manage\shop;


use shop\entities\shop\Category;
use shop\forms\manage\shop\CategoryForm;
use shop\repositories\shop\CategoryRepository;
use shop\repositories\shop\ProductRepository;


class CategoryManageService
{


    private $categories;
    private $products;


    public function __construct(CategoryRepository $categories, ProductRepository $products)
    {
        $this->categories = $categories;
        $this->products = $products;
    }

    /**
     * @param CategoryForm $form
     * @return Category
     */
    public function create(CategoryForm $form)
    {
        /**
         * @var $category Category
         * @var $parent Category
         */

        $parent = $this->categories->get($form->parentId);
        $category = Category::create(
            $form->name,
            $form->title,
            $form->description,
            $form->meta_title,
            $form->meta_description,
            $form->meta_keywords

        );

        $category->appendTo($parent);
        $this->categories->save($category);
        return $category;
    }

    public function edit($id, CategoryForm $form)
    {

        /**
         * @var $category Category
         * @var $parent Category
         */
        $category = $this->categories->get($id);
        $this->assertIsNotRoot($category);
        $category->edit(
            $form->name,
            $form->title,
            $form->description,
            $form->meta_title,
            $form->meta_description,
            $form->meta_keywords,
            $form->status
        );


        if ($form->parentId !== $category->parent->id) {
            $parent = $this->categories->get($form->parentId);
            $category->appendTo($parent);
        }
        $this->categories->save($category);
    }

    /**
     * @param $id
     */
    public function moveUp($id)
    {
        /**
         * @var $category Category
         */
        $category = $this->categories->get($id);
        $this->assertIsNotRoot($category);
        if ($prev = $category->prev) {
            $category->insertBefore($prev);
        }
        $this->categories->save($category);
    }

    public function moveDown($id)
    {
        /**
         * @var $category Category
         */

        $category = $this->categories->get($id);
        $this->assertIsNotRoot($category);
        if ($next = $category->next) {
            $category->insertAfter($next);
        }
        $this->categories->save($category);
    }


    public function remove($id)
    {
        /**
         * @var $category Category
         */
        $category = $this->categories->get($id);
        $this->assertIsNotRoot($category);
//        if ($this->products->existsByMainCategory($category->id)) {
//            throw new \DomainException('Unable to remove category with products.');
//        }
        $this->categories->remove($category);
    }


    /**
     * @param $id
     */
    public function changeStatus($id)
    {
        /**
         * @var $category Category
         */
        $category = $this->categories->get($id);

        if ($category->status === Category::CATEGORY_ACTIVE) {

            $category->updateAttributes(['status' => Category::CATEGORY_DRAFT]);
        } else {

            $category->updateAttributes(['status' => Category::CATEGORY_ACTIVE]);

        }

    }

    /**
     * @param Category $category
     */
    private function assertIsNotRoot(Category $category)
    {
        if ($category->isRoot()) {
            throw new \DomainException('Unable to manage the root category.');
        }
    }

}