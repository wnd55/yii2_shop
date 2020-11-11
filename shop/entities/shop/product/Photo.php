<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 22.03.18
 * Time: 19:26
 */

namespace shop\entities\shop\product;


use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\imagine\Image;



/**
 * @property integer $id
 * @property integer $product_id
 * @property string $file
 * @property integer $sort
 */
class Photo extends ActiveRecord
{


    public static function tableName()
    {
        return '{{%shop_photos}}';
    }


    public static function create($productId, $file, $i)
    {
        $photo = new static();
        $photo->product_id = $productId;
        $photo->file = $file;
        $photo->sort = $i;

        return $photo;


    }

    public function uploadResizePhotos($newPictureRandomName)
    {

        try {
            $image = Image::getImagine();

            $newImage = $image->open(Yii::getAlias('@webroot/uploads/uploads/' . $newPictureRandomName));


            $size = $newImage->getSize();

            $ratio = $size->getWidth() / $size->getHeight();


            $width = 100;
            $height = round($width / $ratio);


            Image::thumbnail('@webroot/uploads/uploads/' . $newPictureRandomName, $width, $height)
                ->save(Yii::getAlias('@webroot/uploads/thumbs/admin/' . $newPictureRandomName), ['quality' => 50]);




            $width = 150;
            $height = round($width / $ratio);


            Image::thumbnail('@webroot/uploads/uploads/' . $newPictureRandomName, $width, $height)
                ->save(Yii::getAlias('@webroot/uploads/thumbs/cart_list/' . $newPictureRandomName), ['quality' => 50]);


            $width = 57;
            $height = round($width / $ratio);


            Image::thumbnail('@webroot/uploads/uploads/' . $newPictureRandomName, $width, $height)
                ->save(Yii::getAlias('@webroot/uploads/thumbs/cart_widget_list/' . $newPictureRandomName), ['quality' => 50]);


            $width = 228;
            $height = round($width / $ratio);


            Image::thumbnail('@webroot/uploads/uploads/' . $newPictureRandomName, $width, $height)
                ->save(Yii::getAlias('@webroot/uploads/thumbs/catalog_list/' . $newPictureRandomName), ['quality' => 80]);


            $width = 375;
            $height = round($width / $ratio);


            Image::thumbnail('@webroot/uploads/uploads/' . $newPictureRandomName, $width, $height)
                ->save(Yii::getAlias('@webroot/uploads/thumbs/catalog_product_main/' . $newPictureRandomName), ['quality' => 80]);


            $width = 65;
            $height = round($width / $ratio);


            Image::thumbnail('@webroot/uploads/uploads/' . $newPictureRandomName, $width, $height)
                ->save(Yii::getAlias('@webroot/uploads/thumbs/catalog_product_additional/' . $newPictureRandomName), ['quality' => 80]);


            $width = 1024;
            $height = round($width / $ratio);


            Image::thumbnail('@webroot/uploads/uploads/' . $newPictureRandomName, $width, $height)
                ->save(Yii::getAlias('@webroot/uploads/thumbs/catalog_origin/' . $newPictureRandomName), ['quality' => 80]);

        } catch (\DomainException $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        return;
    }


    public function unlinkPhotos($id)
    {


        $photos = Photo::findAll(['product_id' => $id]);


        foreach ($photos as $photo) {

            if ($photo->file) {

                unlink((\Yii::getAlias('@webroot/uploads/uploads/' . $photo->file)));

                unlink((\Yii::getAlias('@webroot/uploads/thumbs/admin/' . $photo->file)));



                unlink((\Yii::getAlias('@webroot/uploads/thumbs/cart_list/' . $photo->file)));

                unlink((\Yii::getAlias('@webroot/uploads/thumbs/cart_widget_list/' . $photo->file)));

                unlink((\Yii::getAlias('@webroot/uploads/thumbs/catalog_list/' . $photo->file)));

                unlink((\Yii::getAlias('@webroot/uploads/thumbs/catalog_product_main/' . $photo->file)));

                unlink((\Yii::getAlias('@webroot/uploads/thumbs/catalog_product_additional/' . $photo->file)));

                unlink((\Yii::getAlias('@webroot/uploads/thumbs/catalog_origin/' . $photo->file)));

            }

        }
    }


    public function getCatalogListPhotoFileUrl($productId, $photoId)
    {

        $photo = Photo::find()->where(['product_id' => $productId])->andWhere(['id' => $photoId])->one();

        return Yii::$app->homeUrl.'/backend/web/uploads/thumbs/catalog_list/'.$photo->file;


    }

    public function getCatalogOriginPhotoFileUrl($productId, $photoId)
    {

        $photo = Photo::find()->where(['product_id' => $productId])->andWhere(['id' => $photoId])->orderBy('sort')->one();

        return Url::toRoute(@backend . '/web/uploads/thumbs/catalog_origin/' . $photo->file);


    }


    public function getCatalogProductMainPhotoFileUrl($productId, $photoId)
    {
        $photo = Photo::find()->where(['product_id' => $productId])->andWhere(['id' => $photoId])->orderBy('sort')->one();

        return Url::toRoute(@backend . '/web/uploads/thumbs/catalog_product_main/' . $photo->file);


    }


    public function getCatalogProductAdditionalPhotoFileUrl($productId, $photoId)
    {
        $photo = Photo::find()->where(['product_id' => $productId])->andWhere(['id' => $photoId])->orderBy('sort')->one();

        return Url::toRoute(@backend . '/web/uploads/thumbs/catalog_product_additional/' . $photo->file);


    }


    public function getCartListPhotoFileUrl($productId, $photoId)
    {

        $photo = Photo::find()->where(['product_id' => $productId])->andWhere(['id' => $photoId])->orderBy('sort')->one();

        return Url::toRoute(@backend . '/web/uploads/thumbs/cart_list/' . $photo->file);

    }


    public function getCartWidgetListPhotoFileUrl($productId, $photoId)
    {
        $photo = Photo::find()->where(['product_id' => $productId])->andWhere(['id' => $photoId])->orderBy('sort')->one();

        return Url::toRoute(@backend . '/web/uploads/thumbs/cart_widget_list/' . $photo->file);

    }


    public function getAdminThumbPhotoFileUrl($productId, $photoId)
    {

        $photo = Photo::find()->where(['product_id' => $productId])->andWhere(['id' => $photoId])->orderBy('sort')->one();

        return Url::toRoute('/uploads/thumbs/thumb/' . $photo->file);

    }


    public function getAdminCatalogOriginPhotoFileUrl($productId, $photoId)
    {

        $photo = Photo::find()->where(['product_id' => $productId])->andWhere(['id' => $photoId])->orderBy('sort')->one();

        return Url::toRoute('/uploads/thumbs/catalog_origin/' . $photo->file);


    }


    public function getAdminCatalogFileUrl($productId, $photoId)
    {

        $photo = Photo::find()->where(['product_id' => $productId])->andWhere(['id' => $photoId])->orderBy('sort')->one();

        return Url::toRoute('/uploads/thumbs/cart_list/' . $photo->file);

    }


    public function isIdEqualTo($photoId)
    {

        return $this->id == $photoId;


    }


    public function setSort($sort)
    {
        $this->sort = $sort + 1;

        $this->save();

    }


    public function movePhotoUp($productId, $photoId)
    {


        $photos = Photo::find()->where(['product_id' => $productId])->orderBy('sort')->all();

        foreach ($photos as $i => $photo) {
            if ($photo->isIdEqualTo($photoId)) {

                if ($prev = isset($photos[$i - 1]) ? $photos[$i - 1] : null) {

                    $photos[$i - 1] = $photo;
                    $photos[$i] = $prev;

                    $this->updatePhotos($photos);
                }
                return;
            }
        }
        throw new \DomainException('Photo is not found.');
    }


    public function movePhotoDown($productId, $photoId)
    {


        $photos = Photo::find()->where(['product_id' => $productId])->orderBy('sort')->all();

        foreach ($photos as $i => $photo) {

            if ($photo->isIdEqualTo($photoId)) {


                if ($next = isset($photos[$i + 1]) ? $photos[$i + 1] : null) {


                    $photos[$i + 1] = $photo;
                    $photos[$i] = $next;

                    $this->updatePhotos($photos);
                }

            }

        }


    }

    private function updatePhotos(array $photos)
    {
        foreach ($photos as $i => $photo) {
            $photo->setSort($i);
        }

    }


    public function removePhoto($productId, $photoId)
    {
        $photo = Photo::find()->where(['product_id' => $productId])->andWhere(['id' => $photoId])->orderBy('sort')->one();

        unlink((\Yii::getAlias('@webroot/uploads/uploads/' . $photo->file)));


        unlink((\Yii::getAlias('@webroot/uploads/thumbs/admin/' . $photo->file)));



        unlink((\Yii::getAlias('@webroot/uploads/thumbs/cart_list/' . $photo->file)));

        unlink((\Yii::getAlias('@webroot/uploads/thumbs/cart_widget_list/' . $photo->file)));

        unlink((\Yii::getAlias('@webroot/uploads/thumbs/catalog_list/' . $photo->file)));

        unlink((\Yii::getAlias('@webroot/uploads/thumbs/catalog_product_main/' . $photo->file)));

        unlink((\Yii::getAlias('@webroot/uploads/thumbs/catalog_product_additional/' . $photo->file)));

        unlink((\Yii::getAlias('@webroot/uploads/thumbs/catalog_origin/' . $photo->file)));


        if ($photo->delete() !== false) {

            $photos = Photo::find()->where(['product_id' => $productId])->orderBy('sort')->all();


            $this->updatePhotos($photos);

        }


    }

}