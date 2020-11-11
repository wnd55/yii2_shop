<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 26.02.18
 * Time: 19:16
 */

namespace shop\readModels\blog;


use shop\entities\blog\Category;
use shop\entities\blog\post\Post;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * Class PostReadRepository
 * @package shop\readModels\blog
 */
class PostReadRepository
{

    private function getProvider(ActiveQuery $query)
    {
        return new ActiveDataProvider([

            'query' => $query,
            'sort'=>false,
        ]);

    }

    /**
     * @return ActiveDataProvider
     */
    public function getAll()
    {

        $query = Post::find()->active()->with('category');

        return $this->getProvider($query);


    }


    /**
     * @param $id
     * @return array|null|Post|\yii\db\ActiveRecord
     */
    public function find($id)
    {
        return Post::find()->active()->andWhere(['id' => $id])->one();
    }

    /**
     * @return int|string
     */
    public function count()
    {
        return Post::find()->active()->count();
    }


    /**
     * @param Category $category
     * @return ActiveDataProvider
     */
    public function getAllByCategory(Category $category)
    {
        $query = Post::find()->active()->andWhere(['category_id' => $category->id])->with('category');

        return $this->getProvider($query);
    }

    /**
     * @param $limit
     * @return array|Post[]|\yii\db\ActiveRecord[]
     */
    public function getLast($limit)
    {

        return Post::find()->active()->orderBy(['created_at' => SORT_DESC])->limit($limit)->all();



    }


    /**
     * @param $slug
     * @return array|null|Post|\yii\db\ActiveRecord
     */
    public function findBySlug($slug)
    {
        return Post::find()->active()->andWhere(['slug' => $slug])->one();


    }




}