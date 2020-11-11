<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 19.02.18
 * Time: 18:56
 */


namespace shop\services\manage\blog;


use shop\entities\blog\Category;
use shop\forms\manage\blog\CategoryForm;
use shop\repositories\blog\CategoryRepository;


/**
 * Class CategoryManageService
 * @package shop\services\manage\blog
 */
class CategoryManageService
{
    private $categories;
   // private $posts;


    public function __construct(CategoryRepository $categories)
    {
        $this->categories = $categories;

    }





    public function create(CategoryForm $form)
    {

        $category = Category::create(

            $form->name,
            $form->title,
            $form->description,
            $form->sort,
            $form->meta_title,
            $form->meta_description,
            $form->meta_keyword

        );


        $this->categories->save($category);
        return $category;


    }


    public function edit($id, CategoryForm $form)
    {

        $category = $this->categories->get($id);

        $category->edit(

            $form->name,
            $form->title,
            $form->description,
            $form->sort,
            $form->meta_title,
            $form->meta_description,
            $form->meta_keyword
        );


         $this->categories->save($category);


    }

    public function remove($id)
    {
        $category = $this->categories->get($id);

        $this->categories->remove($category);
    }



}