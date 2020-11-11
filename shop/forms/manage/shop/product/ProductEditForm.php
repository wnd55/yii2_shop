<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 22.03.18
 * Time: 14:16
 */
namespace shop\forms\manage\shop\product;
use shop\entities\shop\Brand;
use shop\entities\shop\Category;
use shop\entities\shop\product\Product;
use shop\entities\shop\product\ProductVariant;
use shop\forms\CompositeForm;
use shop\forms\manage\shop\BrandForm;
use yii\helpers\ArrayHelper;


/**
 * @property TagsForm $tags
 * @property CategoryAssignmentForm $categories
 * @property PhotosForm $photos
 * @property ProductVariantForm $product_variant_id
 * @property BrandForm $brandId
 * @property DimensionsForm $dimensions
 */

class ProductEditForm extends CompositeForm
{

    public $brandId;
    public $code;
    public $name;
    public $short_description;
    public $description;
    public $product_variant_id;
    public $meta_title;
    public $meta_description;
    public $meta_keywords;


    private $_product;

    /**
     * ProductEditForm constructor.
     * @param Product $product
     * @param array $config
     */
    public function __construct(Product $product, $config = [])
    {
        $this->brandId = $product->brand_id;
        $this->code = $product->code;
        $this->name = $product->name;
        $this->short_description = $product->short_description;
        $this->description = $product->description;
        $this->product_variant_id = $product->product_variant_id;
        $this->meta_title = $product->meta_title;
        $this->meta_description = $product->meta_description;
        $this->meta_keywords = $product->meta_keywords;
        $this->categories = new CategoryAssignmentForm($product);
        $this->tags = new TagsForm($product);
        $this->dimensions = new DimensionsForm($product);



        $this->_product = $product;

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['brandId', 'code', 'name', 'meta_title', 'meta_description', 'meta_keywords'], 'required'],
            [['brandId',], 'integer'],
            [['code', 'name'], 'string', 'max' => 255],
            [['code'], 'unique', 'targetClass' => Product::class, 'filter' => $this->_product ? ['<>', 'id', $this->_product->id] : null],
            ['short_description', 'string'],
            ['description', 'string'],

        ];
    }

    /**
     * @return array
     */
    public function brandList()
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
        return ['tags', 'categories', 'dimensions'];
    }

}