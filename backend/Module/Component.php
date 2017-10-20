<?php
/**
 * Created by PhpStorm.
 * User: maltsev
 * Date: 19.10.2017
 * Time: 14:47
 */

namespace App\Module;

abstract class Component
{
    /**
     * init contructor
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        $props = $this->getProps();

        foreach ($params as $key => $value) {
            if (in_array($key, $props)) {
                $this->{$key} = $value;
            } else {
                throw new \Exception("Undefined property $key in class " . get_class($this));
            }
        }
    }

    /**
     * Get all property IS_PROTECTED
     *
     * @return array
     */
    private function getProps()
    {
        $infoClass = new \ReflectionClass($this);
        $propsArray = $infoClass->getProperties(\ReflectionProperty::IS_PROTECTED);
        $className = get_called_class();

        $outProps = [];

        foreach ($propsArray as $item) {
            if ($item->class == $className) {
                $outProps[] = $item->name;
            }
        }

        return $outProps;
    }
}