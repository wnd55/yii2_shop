<?php

namespace shop\entities\shop\queries;

use yii\db\ActiveQuery;

class DeliveryQuery extends ActiveQuery
{
    public function availableForWeight($weight)
    {
        return $this->andWhere(['and',
            ['or', ['min_weight' => null], ['<=', 'min_weight', $weight]],
            ['or', ['max_weight' => null], ['>=', 'max_weight', $weight]],
        ]);
    }
}