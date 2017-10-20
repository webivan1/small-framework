<?php
/**
 * Created by PhpStorm.
 * User: maltsev
 * Date: 19.10.2017
 * Time: 14:30
 */

namespace App\Module;

use App\Module\AppModule;

/**
 * Save object AppModule
 */
class AppContainer
{
    /**
     * @property AppModule
     */
    private static $instance;

    private function __construct()
    {
    }

    private function __wakeup()
    {
        // TODO: Implement __wakeup() method.
    }

    private function __sleep()
    {
        // TODO: Implement __sleep() method.
    }

    /**
     * init class
     *
     * @param AppModule|null $module
     * @return AppModule|null
     */
    public static function getInstance(AppModule $module = null)
    {
        if (!self::$instance) {
            self::$instance = $module;
        }

        return self::$instance;
    }
}