<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 20.03.18
 * Time: 22:12
 */

namespace shop\forms\manage\shop\product;


use shop\entities\shop\Brand;
use shop\entities\shop\Category;
use shop\entities\shop\product\Product;
use shop\entities\shop\product\ProductVariant;
use shop\forms\CompositeForm;
use yii\base\Model;
use yii\helpers\ArrayHelper;


/**
 * @property TagsForm $tags
 * @property DimensionsForm $dimensions
 * @property CategoryAssignmentForm $categories
 * @property PhotosForm $photos
 */
class ProductCreateForm extends CompositeForm
{

    public $brandId;
    public $code;
    public $name;
    public $short_description;
    public $description;
    public $product_variant_id;
    public $quantity;
    public $meta_title;
    public $meta_description;
    public $meta_keywords;

    /**
     * ProductCreateForm constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->code = rand(10000, 1000000);
        $this->categories = new CategoryAssignmentForm();
        $this->tags = new TagsForm();
        $this->photos = new PhotosForm();
        $this->dimensions = new DimensionsForm();


        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['brandId', 'name', 'meta_title', 'meta_description', 'meta_keywords',], 'required'],
            [['code', 'name', 'meta_title', 'meta_description', 'meta_keywords'], 'string', 'max' => 255],
            [['brandId'], 'integer'],
            [['code'], 'unique', 'targetClass' => Product::class],
            ['short_description', 'string'],
            ['description', 'string'],
            [['quantity'], 'integer', 'min' => 0],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'brand_id' => 'Бренд',
            'created_at' => 'Дата создания',
            'code' => 'Коде',
            'name' => 'Назание',
            'short_description' => 'Короткое описание',
            'description' => 'Полное описание',
            'rating' => 'Rating',
            'status' => 'Статус',
            'quantity' => 'Количество',
            'main_photo_id' => 'Main Photo ID',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
            'product_variant_id' => 'Product Variant ID',
            'slug' => 'Slug',
        ];
    }



    /**
     * @return array
     */
    public function brandsList()
    {
        return ArrayHelper::map(Brand::find()->orderBy('name')->asArray()->all(), 'id', 'name');
    }

    /**
     * @return array
     */
    public function categoryList()
    {

        return ArrayHelper::map(Category::find()->orderBy('name')->asArray()->all(), 'id', 'name');


    }

    /**
     * @return array
     */
    public function productVariantList()
    {
        return ArrayHelper::map(ProductVariant::find()->orderBy('name')->asArray()->all(), 'id', 'name');

    }

    /**
     * @return array
     */
    protected function internalForms()
    {
        return ['tags', 'photos', 'categories', 'dimensions'];
    }


}