<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 19.03.18
 * Time: 12:46
 */

namespace shop\entities\shop\product;

use Yii;
use shop\entities\shop\Discount;
use shop\entities\shop\DiscountItem;
use shop\entities\shop\product\queries\ProductQuery;
use shop\entities\user\WishlistItem;
use shop\entities\shop\Brand;
use shop\entities\shop\Category;
use shop\entities\shop\Tag;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use yii\imagine\Image;
use yii\web\UploadedFile;


/**
 * @property integer $id
 * @property integer $created_at
 * @property string $code
 * @property string $name
 * @property string $slug
 * @property string $short_description
 * @property string $description
 * @property integer $category_id
 * @property integer $brand_id
 * @property integer $product_variant_id
 * @property integer $rating
 * @property integer $main_photo_id
 * @property integer $status
 * @property integer $quantity
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property Brand $brand
 * @property Category $category
 * @property CategoryAssignment[] $categoryAssignments
 * @property Category[] $categories
 * @property TagAssignment[] $tagAssignments
 * @property ShopDimensionsAssignments[] $dimensionsAssignments
 * @property Tag[] $tags
 * @property Photo[] $photos
 * @property Photo $mainPhoto
 * @property Photo $file
 * @property ProductVariant $productVariant
 * @property DiscountItem[] $productDiscountItem
 * @property Discount $productDiscount
 * @property Discount $productDiscountActive
 * @property Review[] $reviews
 */
class Product extends ActiveRecord
{


    const STATUS_DRAFT = 0;
    const STATUS_ACTIVE = 1;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%shop_products}}';
    }

    /**
     * @param $brandId
     * @param $categoryId
     * @param $code
     * @param $name
     * @param $short_description
     * @param $description
     * @param $product_variant_id
     * @param $quantity
     * @param $meta_title
     * @param $meta_description
     * @param $meta_keywords
     * @return static
     */
    public static function create($brandId, $categoryId, $code, $name, $short_description, $description, $product_variant_id, $quantity, $meta_title, $meta_description, $meta_keywords)
    {
        $product = new static();

        $product->brand_id = $brandId;
        $product->category_id = $categoryId;
        $product->code = $code;
        $product->name = $name;
        $product->short_description = $short_description;
        $product->description = $description;
        $product->product_variant_id = 1;
        $product->quantity = $quantity;
        $product->meta_title = $meta_title;
        $product->meta_description = $meta_description;
        $product->meta_keywords = $meta_keywords;


        $product->status = self::STATUS_DRAFT;
        $product->created_at = time();

        return $product;

    }

    public static function find()
    {
        return new ProductQuery(static::class);
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [


            'slug' => [
                'class' => SluggableBehavior::class,
                'attribute' => 'name',
                'ensureUnique' => true,
                'immutable' => false

            ]

        ];
    }

    /**
     * @param $brandId
     * @param $categoryId
     * @param $code
     * @param $name
     * @param $short_description
     * @param $description
     * @param $meta_title
     * @param $meta_description
     * @param $meta_keywords
     */
    public function edit($brandId, $categoryId, $code, $name, $short_description, $description,  $meta_title, $meta_description, $meta_keywords)
    {

        $this->brand_id = $brandId;
        $this->category_id = $categoryId;
        $this->code = $code;
        $this->name = $name;
        $this->short_description = $short_description;
        $this->description = $description;
        $this->meta_title = $meta_title;
        $this->meta_description = $meta_description;
        $this->meta_keywords = $meta_keywords;


    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'brand_id' => 'Brand ID',
            'created_at' => 'Дата создания',
            'code' => 'Код',
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

    public function activate()
    {
        if ($this->isActive()) {
            throw new \DomainException('Product is already active.');
        }
        $this->status = self::STATUS_ACTIVE;
    }

    public function isActive()
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function draft()
    {
        if ($this->isDraft()) {
            throw new \DomainException('Product is already draft.');
        }
        $this->status = self::STATUS_DRAFT;
    }

    public function isDraft()
    {
        return $this->status == self::STATUS_DRAFT;
    }

    public function isAvailable()

    {

        return $this->quantity > 0;

    }

    /**
     * @param $quantity
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */


    public function checkout($quantity)
    {

        if ($quantity > $this->quantity) {
            throw new \DomainException('Only ' . $this->quantity . ' items are available.');
        }

        $this->changeOrderQuantity($quantity);


    }

    /**
     * @param $quantity
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */

    public function changeOrderQuantity($quantity)
    {


        $this->quantity -= $quantity;
        $this->update();

    }

    public function changeQuantity($quantity)
    {

        $this->setQuantity($quantity);
    }

    public function setQuantity($quantity)
    {


        $this->quantity = $quantity;

    }


    //Reviews

    public function canBeCheckout($quantity)
    {


        return $quantity <= $this->quantity;

    }

    public function reviewsCount($id)
    {

        return $count = Review::find()->where(['product_id' => $id])->andWhere(['active' => true])->count();

    }

    ################


    //Unlink CategoryAssignment

    public function addRating(Product $product)
    {
        $reviews = $product->reviews;

        $amount = 0;
        $total = 0;
        foreach ($reviews as $review) {

            if ($review->isActive()) {

                $amount++;
                $total += $review->vote;
            }
        }
        $rating = $amount ? $total / $amount : null;
        $this->updateAttributes(['rating' => $rating]);

    }


    //Unlink TagAssignment

    public function unlinkCategoryAssignment($id)
    {
        $categories = CategoryAssignment::find()->where(['product_id' => $id])->all();

        foreach ($categories as $category) {

            $category->delete();
        }

    }


    #############################################################################################################

    /**
     * @param $id
     */
    public function unlinkTagAssignment($id)
    {

        $tags = TagAssignment::find()->where(['product_id' => $id])->all();

        foreach ($tags as $tag) {

            $tag->delete();

        }
    }

    /**
     * @param $id
     */
    public function unlinkDimensionsAssignment($id)
    {
       $dimensions = ShopDimensionsAssignments::find()->where(['product_id' => $id])->all();

       foreach ($dimensions as $dimension){

           $dimension->delete();
       }

    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrand()
    {
        return $this->hasOne(Brand::class, ['id' => 'brand_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryAssignments()
    {

        return $this->hasMany(CategoryAssignment::class, ['product_id' => 'id']);
    }


    public function getCategories()
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])->via('categoryAssignments');

    }

    public function getDimensions()
    {
        return $this->hasMany(ShopProductsDimensions::class, ['id' => 'dimension_id'])->via('dimensionsAssignments');

    }
    public function getDimensionsAssignments()
    {
        return $this->hasMany(ShopDimensionsAssignments::class, ['product_id' => 'id']);
    }

    public function getTagAssignments()
    {
        return $this->hasMany(TagAssignment::class, ['product_id' => 'id']);
    }

    public function getTags()
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])->via('tagAssignments');
    }

    public function getPhotos()
    {
        return $this->hasMany(Photo::class, ['product_id' => 'id'])->orderBy('sort');
    }

    public function getMainPhoto()
    {
        return $this->hasOne(Photo::class, ['id' => 'main_photo_id']);

    }

    public function getWishlistItems()
    {
        return $this->hasMany(WishlistItem::class, ['product_id' => 'id']);
    }

    public function getProductVariant()
    {

        return $this->hasOne(ProductVariant::class, ['id' => 'product_variant_id']);

    }

    public function getProductDiscountItem()
    {

        return $this->hasMany(DiscountItem::class, ['product_id' => 'id']);

    }

    public function getProductDiscount()
    {

        return $this->hasOne(Discount::class, ['id' => 'discount_id'])->via('productDiscountItem');
    }

    public function getProductDiscountActive()
    {

        return $this->hasOne(Discount::class, ['id' => 'discount_id'])->where(['active' => true])->via('productDiscountItem');
    }



    public function getReviews()
    {

        return $this->hasMany(Review::class, ['product_id' => 'id']);

    }
    ##########################################################################################################
    public function afterSaveAddMainPhoto($id)
    {

        $photo = Photo::find()->where(['product_id' => $id])->orderBy('sort')->one();

        $this->updateAttributes(['main_photo_id' => $photo->id]);

    }


    public function ChangeMainPhoto($photoId)
    {

        $this->updateAttributes(['main_photo_id' => $photoId]);


    }
}