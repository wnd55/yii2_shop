<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 26.02.18
 * Time: 20:59
 */

namespace shop\entities\blog\post;

use yii\db\ActiveRecord;


/**
 * @property int $id
 * @property int $created_at
 * @property int $post_id
 * @property int $user_id
 * @property int $parent_id
 * @property string $text
 * @property bool $active
 *
 * @property Post $post
 */
class Comment extends ActiveRecord
{

    const COMMENT_STATUS_ACTIVE = 1;
    const COMMENT_STATUS_DRAFT = 0;


    public static function tableName()
    {
        return '{{%blog_comments}}';
    }

    /**
     * @param $postId
     * @param $userId
     * @param $parentId
     * @param $text
     * @return static
     */
    public static function create($postId, $userId, $parentId, $text)
    {
        $review = new static();

        $review->post_id = $postId;
        $review->user_id = $userId;
        $review->parent_id = $parentId;
        $review->text = $text;
        $review->created_at = time();
        $review->active = self::COMMENT_STATUS_DRAFT;

        $review->save();

        return $review;
    }

    public function activate()
    {
        $this->active = true;
    }

    public function draft()
    {
        $this->active = false;
    }


    public function isActive()
    {

        return $this->active == true;
    }


    public function isIdEqualTo($id)
    {

        return $this->id == $id;

    }


    public function isChildOf($id)
    {
        return $this->parent_id == $id;
    }


    public function getPost()
    {
        return $this->hasOne(Post::class, ['id' => 'post_id']);
    }
}