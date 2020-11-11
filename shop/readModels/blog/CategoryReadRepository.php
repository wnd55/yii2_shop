<?php

namespace shop\readModels\blog;

use shop\entities\blog\Category;

/**
 * Class CategoryReadRepository
 * @package shop\readModels\blog
 */
class CategoryReadRepository
{
    public function getAll()
    {
        return Category::find()->orderBy('sort')->all();
    }

    public function find($id)
    {
        return Category::findOne($id);
    }

    public function findBySlug($slug)
    {
        return Category::find()->andWhere(['slug' => $slug])->one();
    }
}