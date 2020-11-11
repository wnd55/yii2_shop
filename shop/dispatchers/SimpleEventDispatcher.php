<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 07.05.19
 * Time: 14:00
 */

namespace shop\dispatchers;


use shop\listeners\user\UserSignupRequestedListener;

class SimpleEventDispatcher implements EventDispatcher
{


    private $listeners;


    public function __construct(array $listeners)
    {

        $this->listeners = $listeners;
    }


    public function dispatch($event)
    {
        $eventName = get_class($event);
        if (array_key_exists($eventName, $this->listeners)) {

            foreach ($this->listeners[$eventName] as $listenerClass) {

              $listener = [\Yii::$container->get($listenerClass), 'handler'];
              $listener($event);
            }
        }

    }


}