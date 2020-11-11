<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 14.02.19
 * Time: 11:47
 */

namespace shop\repositories;


use shop\entities\Page;
use yii\web\NotFoundHttpException;

class PageRepository

{

    /**
     * @param $id
     * @return null|static
     * @throws NotFoundHttpException
     */
    public function get($id)
    {
        if (!$page = Page::findOne($id)) {
            throw new NotFoundHttpException('Page is not found.');
        }
        return $page;
    }

    /**
     * @param Page $page
     */

    public function save(Page $page)
    {
        if (!$page->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    /**
     * @param Page $page
     */
    public function remove(Page $page)
    {
        if (!$page->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }


}