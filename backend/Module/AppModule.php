<?php
/**
 * Created by PhpStorm.
 * User: maltsev
 * Date: 19.10.2017
 * Time: 11:13
 */

namespace App\Module;

class AppModule
{
    /**
     * Config
     *
     * @property array
     */
    private static $initClasses = [];

    /**
     * init
     *
     * @param $configParams
     */
    public function __construct(array $configParams)
    {
        foreach ($configParams as $key => $items) {
            $this->initConfigItem(
                $key,
                !empty($items['class']) ? $items['class'] : null,
                array_diff_key($items, ['class' => 1])
            );
        }
    }

    /**
     * Save item config
     *
     * @param string key - name config
     * @param string|null $className - class name
     * @param array $params
     */
    private function initConfigItem($key, $className, array $params = [])
    {
        if (!is_null($className) && class_exists($className)) {
            self::$initClasses[$key] = function () use ($className, $params) {
                return new $className($params);
            };
        } else {
            self::$initClasses[$key] = $params;
        }
    }

    /**
     * @return array
     */
    public function getConfigure()
    {
        return self::$initClasses;
    }

    /**
     * Call item initClasses
     *
     * @return array|Component
     */
    public function __call($name, $arguments)
    {
        if (strpos($name, 'get') === 0 && empty($arguments)) {
            $paramConfig = lcfirst(str_replace('get', '', $name));

            if (array_key_exists($paramConfig, self::$initClasses)) {
                if (is_callable(self::$initClasses[$paramConfig])) {
                    return self::$initClasses[$paramConfig] = call_user_func(self::$initClasses[$paramConfig]);
                } else {
                    return self::$initClasses[$paramConfig];
                }
            }
        }
    }

    /**
     * Call item initClasses
     *
     * @return array|Component
     */
    public function __get($name)
    {
        if (array_key_exists($name, self::$initClasses)) {
            if (is_callable(self::$initClasses[$name])) {
                return self::$initClasses[$name] = call_user_func(self::$initClasses[$name]);
            } else {
                return self::$initClasses[$name];
            }
        }
    }

    /**
     * Run router
     *
     * @return void
     */
    public function run()
    {
        $this->getRouter();
    }
}