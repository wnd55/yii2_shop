<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 19.03.18
 * Time: 14:02
 */

namespace shop\repositories\shop;

use shop\entities\shop\Tag;


/**
 * @property integer $id
 * @property string $name
 * @property string $slug
 */
class TagRepository
{
    public function get($id)
    {

        if (!$tag = Tag::findOne($id)) {

            throw new \DomainException('Tag is not found');
        }

        return $tag;
    }

    public function save(Tag $tag)
    {

        if (!$tag->save()) {

            throw new \RuntimeException('Saving error');
        }

    }


    public function remove(Tag $tag)
    {


        if (!$tag->delete()) {

            throw new \RuntimeException('Removing error');
        }

    }

    public function findByName($name)
    {

        return Tag::findOne(['name' => $name]);

    }


    public function findNewTag($name)
    {

        $tag = Tag::find()->andWhere(['name' => $name])->one();

        return $tag;

    }


}