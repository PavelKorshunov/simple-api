<?php

namespace App\Helpers;

class ControllerResolver
{
    /**
     * @param string $controller
     *
     * @return object|array
     */
    public function createController(string $controller)
    {
        if (false === strpos($controller, '::')) {
            return $this->instantiateController($controller);
        }

        list($class, $method) = explode('::', $controller, 2);

        return array($this->instantiateController($class), $method);
    }

    /**
     * Returns an instantiated controller.
     *
     * @param string $class A class name
     *
     * @return object
     */
    protected function instantiateController(string $class)
    {
        return new $class();
    }
}