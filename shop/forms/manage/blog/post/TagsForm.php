<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 20.02.18
 * Time: 16:32
 */

namespace shop\forms\manage\blog\post;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use shop\entities\blog\post\Post;
use shop\entities\blog\Tag;

/**
 * @property array $newNames
 */
class TagsForm extends Model
{

    public $existing = [];
    public $textNew;

    public function __construct(Post $post = null, $config = [])
    {
        if ($post) {
            $this->existing = ArrayHelper::getColumn($post->tagAssignments, 'tag_id');
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            ['existing', 'each', 'rule' => ['integer']],
            ['textNew', 'string'],
            ['existing', 'default', 'value' => []],
        ];
    }

    public function tagsList()
    {
        return ArrayHelper::map(Tag::find()->orderBy('name')->asArray()->all(), 'id', 'name');
    }

    public function getNewNames()
    {
        return array_filter(array_map('trim', preg_split('#\s*,\s*#i', $this->textNew)));
    }




}