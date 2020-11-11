<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 19.03.18
 * Time: 13:34
 */

namespace shop\forms\manage\shop;


use shop\entities\shop\Tag;
use yii\base\Model;

class TagForm extends Model

{

    public $name;
    private $_tag;

    /**
     * TagForm constructor.
     * @param Tag|null $tag
     * @param array $config
     */

    public function __construct(Tag $tag = null, array $config = [])
    {
        if ($tag) {

            $this->name = $tag->name;
            $this->_tag = $tag;

        }


        parent::__construct($config);
    }


    public function rules()
    {
        return [

            [['name'], 'string', 'max' => 255],
            [['name'], 'required'],
            [['name'], 'unique', 'targetClass' => Tag::class,
                'filter' => $this->_tag ? ['<>', 'id', $this->_tag->id] : null]

        ];
    }


}