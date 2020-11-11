<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 19.02.18
 * Time: 19:06
 */

namespace shop\repositories\blog;

use shop\entities\blog\Category;


/**
 * Class CategoryRepository
 * @package shop\repositories\Blog
 */
class CategoryRepository
{

    public function get($id)
    {

        if(!$category = Category::findOne($id)){

            throw new \DomainException('Category is not found.');

        }

        return $category;

    }


    public function save(Category $category)
    {
        if(!$category->save()){

            throw new \RuntimeException('Saving error.');

        }


    }



    public function remove(Category $category)
    {
        if (!$category->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }


}
