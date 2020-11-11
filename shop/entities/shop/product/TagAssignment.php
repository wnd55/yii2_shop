<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 21.03.18
 * Time: 12:02
 */

namespace shop\entities\shop\product;

use yii\db\ActiveRecord;



/**
 * @property integer $product_id;
 * @property integer $tag_id;
 */

class TagAssignment extends ActiveRecord
{


    public static function tableName()
    {
        return '{{%shop_tag_assignments}}';
    }

    public static function create($productId, $tagId)
    {
        $assignment = new static();

        $assignment->product_id = $productId;
        $assignment->tag_id = $tagId;

        return $assignment;
    }

    public function isForTag($id)
    {
        return $this->tag_id == $id;
    }



}
