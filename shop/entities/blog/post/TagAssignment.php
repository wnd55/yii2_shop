<?php


namespace shop\entities\blog\post;

use yii\db\ActiveRecord;

/**
 * @property integer $post_id;
 * @property integer $tag_id;
 */

class TagAssignment extends ActiveRecord
{

    /**
     * @param $postId
     * @param $tagId
     */
    public static function create($postId, $tagId )
    {
        $assignment = new static();

        $assignment->post_id = $postId;
        $assignment->tag_id = $tagId;
        $assignment->save();

    }


    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%blog_tag_assignments}}';
    }





}