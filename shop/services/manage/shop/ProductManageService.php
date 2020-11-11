<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 21.03.18
 * Time: 9:43
 */

namespace shop\services\manage\shop;

use shop\entities\shop\product\ShopDimensionsAssignments;
use Yii;
use shop\entities\shop\product\CategoryAssignment;
use shop\entities\shop\Tag;
use shop\forms\manage\shop\product\PhotosForm;
use shop\repositories\shop\ProductVariantRepository;
use shop\entities\shop\product\Photo;
use shop\entities\shop\product\Product;
use shop\entities\shop\product\TagAssignment;
use shop\forms\manage\shop\product\ProductCreateForm;
use shop\forms\manage\shop\product\ProductEditForm;
use shop\forms\manage\shop\product\QuantityForm;
use shop\repositories\shop\BrandRepository;
use shop\repositories\shop\CategoryRepository;
use shop\repositories\shop\ProductRepository;
use shop\repositories\shop\TagRepository;
use yii\web\UploadedFile;

/**
 * Class ProductManageService
 * @package shop\services\manage\shop
 * @property Product $product
 * @property ProductCreateForm
 * @property ProductEditForm
 *
 */
class ProductManageService
{

    public static $i = 1;

    private $products;
    private $brands;
    private $categories;
    private $tags;


    private $productVariant;

    private $photo;


    public function __construct(ProductRepository $products, BrandRepository $brands, CategoryRepository $categories, TagRepository $tags, Photo $photo, ProductVariantRepository $productVariant)
    {

        $this->products = $products;
        $this->brands = $brands;
        $this->categories = $categories;
        $this->tags = $tags;
        $this->photo = $photo;
        $this->productVariant = $productVariant;


    }

    //Create product

    /**
     * @param ProductCreateForm $form
     * @return static
     */

    public function create(ProductCreateForm $form)
    {

        /**
         * @var $product Product
         */

        $brand = $this->brands->get($form->brandId);

        $category = $this->categories->get($form->categories->main_category);


        $product = Product::create(

            $brand->id,
            $category->id,
            $form->code,
            $form->name,
            $form->short_description,
            $form->description,
            $form->product_variant_id,
            $form->quantity,
            $form->meta_title,
            $form->meta_description,
            $form->meta_keywords


        );


        $this->products->save($product);

        //CategoryAssignment

        foreach ($form->categories->additional_categories as $additional_categoryId) {

            $category = $this->categories->get($additional_categoryId);

            CategoryAssignment::create($product->id, $category->id)->save();
        }


        //ShopDimensionsAssignments
        foreach ($form->dimensions->dimensions as $dimension) {

            ShopDimensionsAssignments::create((int)$product->id, (int)$dimension)->save();
        }

        //TagAssignment

        foreach ($form->tags->existing as $tagId) {

            $tag = $this->tags->get($tagId);

            TagAssignment::create($product->id, $tag->id)->save();
        }

        // Add new Tags

        foreach ($form->tags->newNames as $tagName) {

            if (!$tag = $this->tags->findByName($tagName)) {

                if (!empty($tagName)) {

                    Tag::create($tagName)->save();

                    $tag = $this->tags->findNewTag($tagName);

                    TagAssignment::create($product->id, $tag->id)->save();
                }

            }
        }


        // Photos


        $images = UploadedFile::getInstances($form->photos, 'files');

        foreach ($images as $image) {

            // Генерация random

            $randomName = $image->getBaseName() . Yii::$app->getSecurity()->generateRandomString(7);

            $image->saveAs('uploads/uploads/' . $randomName . '.' . $image->getExtension());

            $newPictureRandomName = $randomName . '.' . $image->getExtension();

            $this->photo->uploadResizePhotos($newPictureRandomName);

            $photo = Photo::create($product->id, $newPictureRandomName, self::$i++);

            if (!$photo->save()) {

                throw new \RuntimeException('Saving error');
            }

        }

        if (isset($photo)) {

            $product->afterSaveAddMainPhoto($product->id);
        }
        return $product;
    }

    /**
     * @param $id
     * @param PhotosForm $photosForm
     */
    public function addPhotos($id, PhotosForm $photosForm)
    {
        /**
         * @var $product Product
         */
        $product = $this->products->get($id);

        $sort = Photo::find()->where(['product_id' => $id])->orderBy('sort')->max('sort') + 1;

        $images = UploadedFile::getInstances($photosForm, 'files');


        foreach ($images as $image) {


            // Генерация random

            $randomName = $image->getBaseName() . Yii::$app->getSecurity()->generateRandomString(7);

            $image->saveAs('uploads/uploads/' . $randomName . '.' . $image->getExtension());

            $newPictureRandomName = $randomName . '.' . $image->getExtension();

            $this->photo->uploadResizePhotos($newPictureRandomName);

            Photo::create($product->id, $newPictureRandomName, $sort++)->save();


        }

        if (!$product->mainPhoto) {

            $product->afterSaveAddMainPhoto($product->id);
        }


    }

    //Edit product

    /**
     * @param $id
     * @param ProductEditForm $form
     */

    public function edit($id, ProductEditForm $form)
    {
        /**
         *  * @var $product Product
         */
        $product = $this->products->get($id);

        $product->unlinkCategoryAssignment($id);
        $product->unlinkTagAssignment($id);
        $product->unlinkDimensionsAssignment($id);


        $brand = $this->brands->get($form->brandId);

        $category = $this->categories->get($form->categories->main_category);

        $product->edit(

            $brand->id,
            $category->id,
            $form->code,
            $form->name,
            $form->short_description,
            $form->description,
            $form->meta_title,
            $form->meta_description,
            $form->meta_keywords


        );

        $this->products->save($product);


        //CategoryAssignment

        foreach ($form->categories->additional_categories as $additional_categoryId) {

            $category = $this->categories->get($additional_categoryId);

            CategoryAssignment::create($product->id, $category->id)->save();
        }


        //TagAssignment

        foreach ($form->tags->existing as $tagId) {

            $tag = $this->tags->get($tagId);

            TagAssignment::create($product->id, $tag->id)->save();
        }


        //ShopDimensionsAssignments
        foreach ($form->dimensions->dimensions as $dimension) {

            ShopDimensionsAssignments::create((int)$product->id, (int)$dimension)->save();
        }

        // Add new Tags

        foreach ($form->tags->newNames as $tagName) {

            if (!$tag = $this->tags->findByName($tagName)) {

                if (!empty($tagName)) {

                    Tag::create($tagName)->save();

                    $tag = $this->tags->findNewTag($tagName);

                    TagAssignment::create($product->id, $tag->id)->save();
                }
            }

        }

    }


    // Remove product

    /**
     * @param $id
     */
    public function remove($id)
    {

        $this->photo->unlinkPhotos($id);

        $product = $this->products->get($id);

        $this->products->remove($product);


    }

    /**
     * @param $id
     */
    public function activate($id)
    {
        $product = $this->products->get($id);
        $product->activate();
        $this->products->save($product);
    }

    /**
     * @param $id
     */
    public function draft($id)
    {
        $product = $this->products->get($id);
        $product->draft();
        $this->products->save($product);
    }

    //Quantity

    /**
     * @param $id
     * @param QuantityForm $form
     */
    public function changeQuantity($id, QuantityForm $form)
    {
        $product = $this->products->get($id);

        $product->setQuantity($form->quantity);

        $this->products->save($product);


    }

////////////////////////////////////////////////////////////////

//Photo

    public function makeMainPhoto($productId, $photoId)
    {


        $product = $this->products->get($productId);

        $product->ChangeMainPhoto($photoId);

    }


    public function movePhotoUp($productId, $photoId)
    {

        $this->photo->movePhotoUp($productId, $photoId);


    }


    public function movePhotoDown($productId, $photoId)
    {

        $this->photo->movePhotoDown($productId, $photoId);


    }


    public function removePhoto($productId, $photoId)
    {

        $this->photo->removePhoto($productId, $photoId);

    }


}