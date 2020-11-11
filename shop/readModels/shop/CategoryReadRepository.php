<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 01.04.18
 * Time: 21:28
 */

namespace shop\readModels\shop;


use shop\entities\shop\Category;
use shop\readModels\shop\views\CategoryView;
use yii\helpers\ArrayHelper;

class CategoryReadRepository
{



    public function getRoot()
    {
        return Category::find()->roots()->one();
    }



    public function getAll()
    {

        return Category::find()->orderBy('depth')->all();



    }



    public function find($id)
    {

        return Category::find()->andWhere(['id' => $id])->andWhere(['>' , 'depth', 0])->one();

    }


    /**
     * @param $slug
     * @return array|null|Category|\yii\db\ActiveRecord
     */

    public function findBySlug($slug)
    {
        return Category::find()->andWhere(['slug' => $slug])->andWhere(['>' , 'depth', 0])->one();
    }



    public function getTreeCategory(Category $category = null)
    {

        $query = Category::find()->andWhere(['>', 'depth', 0])->orderBy('lft');



        if ($category) {
            $criteria = ['or', ['depth' => 1]];
            foreach (ArrayHelper::merge([$category], $category->parents) as $item) {
                $criteria[] = ['and', ['>', 'lft', $item->lft], ['<', 'rgt', $item->rgt], ['depth' => $item->depth + 1]];
            }
            $query->andWhere($criteria);
        } else {
            $query->andWhere(['depth' => 1]);
        }

        return $query->orderBy('name')->all();


    }

}