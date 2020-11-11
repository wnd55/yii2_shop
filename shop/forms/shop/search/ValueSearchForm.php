<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 27.01.19
 * Time: 15:07
 */

namespace shop\forms\shop\search;

use shop\entities\shop\Characteristic;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * @property string $equal
 */
class ValueSearchForm extends Model
{
    public $equal;

    private $_characteristic;

    public function __construct(Characteristic $characteristic, $config = [])
    {
        $this->_characteristic = $characteristic;
        parent::__construct($config);
    }


    public function rules()
    {

        return
            [

                [['equal'], 'string']

            ];

    }

    public function isFilled()
    {
        return !empty($this->equal);
    }


    public function getCharacteristics()
    {
        return Characteristic::find()->all();

    }


    public function variantsList()
    {
        return $this->_characteristic->variants ? array_combine($this->_characteristic->variants, $this->_characteristic->variants) : [];
    }


    public function getCharacteristicName()
    {
        return $this->_characteristic->name;
    }


    public function getId()
    {
        return $this->_characteristic->id;
    }


}