<?php

namespace shop\entities\shop\product;


/**
 * This is the model class for table "shop_dimensions_assignments".
 *
 * @property int $product_id
 * @property int $dimension_id
 *
 * @property ShopProductsDimensions $dimension
 * @property Product $product
 */
class ShopDimensionsAssignments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_dimensions_assignments';
    }

    /**
     * @param $productId
     * @param $dimensionId
     * @return static
     */
    public static function create($productId, $dimensionId)
    {
        $assignment = new static();

        $assignment->product_id = $productId;
        $assignment->dimension_id = $dimensionId;

        return $assignment;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'dimension_id'], 'required'],
            [['product_id', 'dimension_id'], 'integer'],
            [['product_id', 'dimension_id'], 'unique', 'targetAttribute' => ['product_id', 'dimension_id']],
            [['dimension_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopProductsDimensions::className(), 'targetAttribute' => ['dimension_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'dimension_id' => 'Dimension ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDimension()
    {
        return $this->hasOne(ShopProductsDimensions::className(), ['id' => 'dimension_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
