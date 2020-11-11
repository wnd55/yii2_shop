<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 14.02.19
 * Time: 11:50
 */

namespace shop\readModels;


use shop\entities\Page;

class PageReadRepository
{
    /**
     * @return array|Page[]|\yii\db\ActiveRecord[]
     */
    public function getAll()
    {
        return Page::find()->andWhere(['>', 'depth', 0])->all();
    }

    /**
     * @param $id
     * @return null|static
     */
    public function find($id)
    {
        return Page::findOne($id);
    }

    /**
     * @param $slug
     * @return array|null|Page|\yii\db\ActiveRecord
     */
    public function findBySlug($slug)
    {
        return Page::find()->andWhere(['slug' => $slug])->andWhere(['>', 'depth', 0])->one();
    }





}