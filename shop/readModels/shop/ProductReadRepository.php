<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 01.04.18
 * Time: 20:56
 */

namespace shop\readModels\shop;

use shop\entities\shop\Brand;
use shop\entities\shop\Category;
use shop\entities\shop\product\Product;
use shop\entities\shop\Tag;
use shop\forms\manage\shop\BrandForm;
use shop\forms\shop\search\SearchForm;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

class ProductReadRepository
{

    /**
     * @return ActiveDataProvider
     */
    public function getAll()
    {

        $query = Product::find()->joinWith(['mainPhoto', 'productVariant'])->andWhere(['status' => Product::STATUS_ACTIVE,]);

        return $this->getProvider($query);

    }

    /**
     * @param $id
     * @return array|null|Product|\yii\db\ActiveRecord
     */
    public function find($id)
    {
        return Product::find()->active()->andWhere(['id' => $id])->one();


    }


    /**
     * @param $slug
     * @return array|null|Product|\yii\db\ActiveRecord
     */

    public function findBySlug($slug)
    {
        return Product::find()->active()->andWhere(['slug' => $slug])->one();


    }



    /**
     * @param ActiveQuery $query
     * @return ActiveDataProvider
     */

    private function getProvider(ActiveQuery $query)
    {

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => 15
            ],
            'sort' => [
                'defaultOrder' => ['name' => SORT_ASC],
                'attributes' => [
                    'id' => [
                        'asc' => ['id' => SORT_ASC],
                        'desc' => ['id' => SORT_DESC],
                    ],
                    'name' => [
                        'asc' => ['name' => SORT_ASC],
                        'desc' => ['name' => SORT_DESC],
                    ],
                    'price' => [
                        'asc' => ['products_variants.price_new' => SORT_ASC],
                        'desc' => ['products_variants.price_new' => SORT_DESC],
                    ],

                ],
            ],

        ]);


    }

    /**
     * @param Category $category
     * @return ActiveDataProvider
     */

    public function getAllByCategory(Category $category)
    {

        $query = Product::find()->alias('p')->andWhere(['p.' . 'status' => Product::STATUS_ACTIVE,])->with('mainPhoto', 'category');

        $ids = ArrayHelper::merge([$category->id], $category->getDescendants()->select('id')->column());
        $query->joinWith(['categoryAssignments ca'], false);
        $query->andWhere(['or', ['p.category_id' => $ids], ['ca.category_id' => $ids]]);
        $query->groupBy('p.id');


        return $this->getProvider($query);

    }

    /**
     * @param $limit
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getFeatured($limit)
    {

        return Product::find()->with('mainPhoto')->orderBy(new Expression('rand()'))->limit($limit)->all();


    }


    public function getSaleProducts($limit, $category)
    {

        return Product::find()->with('mainPhoto')->joinWith(['categoryAssignments ca' => function(\yii\db\ActiveQuery $query)use($category){$query->andWhere(['ca.category_id' => $category]);}])->orderBy(new Expression('rand()'))->limit($limit)->all();

    }

    /**
     * @param Brand $brand
     * @return ActiveDataProvider
     */
    public function getAllByBrand(Brand $brand)
    {
        $query = Product::find()->alias('p')->active('p')->with('mainPhoto');

        $query->andWhere(['p.brand_id' => $brand->id]);

        return $this->getProvider($query);


    }

    public function getAllByTag(Tag $tag)
    {
        $query = Product::find()->alias('p')->active('p')->with('mainPhoto');
        $query->joinWith(['tagAssignments ta'], false);
        $query->andWhere(['ta.tag_id' => $tag->id]);
        return $this->getProvider($query);

    }

    /**
     * @param SearchForm $form
     * @return ActiveDataProvider
     */

    public function search(SearchForm $form)
    {


        $query = Product::find()->alias('p')->active('p')->with('mainPhoto', 'category')->with('brand');


        if ($form->brand) {

            $query->andWhere(['p.brand_id' => $form->brand]);
        }


        if ($form->category) {

            if ($category = Category::findOne($form->category)) {

                $ids = ArrayHelper::merge([$form->category], $category->getChildren()->select('id')->column());
                $query->joinWith(['categoryAssignments ca'], false);
                $query->andWhere(['or', ['p.category_id' => $ids], ['ca.category_id' => $ids]]);

            } else {

                $query->andWhere(['p.id' => 0]);
            }
        }

        if (!empty($form->text)) {

            $query->andWhere(['or', ['like', 'code', $form->text], ['like', 'name', $form->text], ['like', 'description', $form->text]]);

            if (($brand = Brand::findOne(['name'=> $form->text])) !== null) {

                $query->orWhere( ['p.brand_id' => $brand->id]);
            }

        }

        $query->groupBy('p.id');

        return $dataProvider = new ActiveDataProvider([

            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
                'attributes' => [
                    'id' => [
                        'asc' => ['p.id' => SORT_ASC],
                        'desc' => ['p.id' => SORT_DESC],
                    ],
                    'name' => [
                        'asc' => ['p.name' => SORT_ASC],
                        'desc' => ['p.name' => SORT_DESC],
                    ],


                ],
            ],
            'pagination' => [
                'pageSizeLimit' => [15, 100],
            ]

        ]);


    }

    /**
     * @param $userId
     * @return ActiveDataProvider
     */

    public function getWishList($userId)
    {
        return new ActiveDataProvider([
            'query' => Product::find()
                ->alias('p')->active('p')
                ->joinWith('wishlistItems w', false, 'INNER JOIN')
                ->andWhere(['w.user_id' => $userId]),
            'sort' => false,
        ]);
    }






}