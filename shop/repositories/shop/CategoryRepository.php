<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 19.03.18
 * Time: 18:28
 */

namespace shop\repositories\shop;

use shop\entities\shop\Category;

class CategoryRepository
{
    public function get($id)
    {
        if (!$category = Category::findOne($id)) {


            throw new \DomainException('Категория не найдена.');
        }

        return $category;

    }

    public function save(Category $category)
    {
        if (!$category->save()) {
            throw new \RuntimeException('Ошибка сохранения.');
        }

    }

    public function remove(Category $category)
    {
        if (!$category->delete()) {
            throw new \RuntimeException('Ошибка удаления.');
        }

    }


}