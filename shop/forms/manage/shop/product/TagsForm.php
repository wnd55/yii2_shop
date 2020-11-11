<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 21.03.18
 * Time: 12:08
 */

namespace shop\forms\manage\shop\product;

use shop\entities\shop\product\Product;
use shop\entities\shop\Tag;
use yii\base\Model;
use yii\helpers\ArrayHelper;


/**
 * @property array $newNames
 */


class TagsForm extends Model
{
    public $existing = [];

    //New Tag
    public $textNew;

    public function __construct(Product $product = null, $config = [])
    {
        if ($product) {
            $this->existing = ArrayHelper::getColumn($product->tagAssignments, 'tag_id');
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            ['existing', 'each', 'rule' => ['integer']],
            ['textNew', 'string'],
        ];
    }

    public function tagsList()
    {
        return ArrayHelper::map(Tag::find()->orderBy('name')->asArray()->all(), 'id', 'name');


    }


    public function getNewNames()
    {
        return array_map('trim', preg_split('#\s*,\s*#i', $this->textNew));
    }

    public function beforeValidate()
    {
        $this->existing = array_filter((array)$this->existing);

        return parent::beforeValidate();
    }



}