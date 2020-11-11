<?php
/**
 * Created by PhpStorm.
 * User: webndesign
 * Date: 13.08.20
 * Time: 20:10
 */


namespace shop\entities\shop\product;

use Yii;

/**
 * This is the model class for table "shop_products_dimensions".
 *
 * @property int $id
 * @property string $name
 * @property string $size
 * @property int $price
 *
 * @property ShopDimensionsAssignments[] $shopDimensionsAssignments
 * @property Product[] $products
 */
class ShopProductsDimensions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_products_dimensions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'size'], 'required'],
            [['price'], 'integer'],
            [['name', 'size'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'size' => 'Size',
            'price' => 'Price',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopDimensionsAssignments()
    {
        return $this->hasMany(ShopDimensionsAssignments::className(), ['dimension_id' => 'id']);
    }

//    /**
//     * @return \yii\db\ActiveQuery
//     */
//    public function getProducts()
//    {
//        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('shop_dimensions_assignments', ['dimension_id' => 'id']);
//    }
}
