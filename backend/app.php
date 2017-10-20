<?php
/**
 * Created by PhpStorm.
 * User: maltsev
 * Date: 19.10.2017
 * Time: 11:03
 */

try {

    defined('APP') || define('APP', __DIR__);

    if (is_file(__DIR__ . '/env_dev.txt') || $_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
        $env = 'dev';
    } else {
        $env = 'prod';
    }

    defined('APP_ENV') || define('APP_ENV', $env);

    // register autoload
    require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

    $configFile = require __DIR__ . DIRECTORY_SEPARATOR . 'Http' . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'app.php';

    spl_autoload_register(function ($className) {
        $className = ltrim($className, '\\');

        if (strpos($className, 'App') === 0) {
            $path = APP . str_replace('\\', DIRECTORY_SEPARATOR, preg_replace('/^App/', '', $className)) . '.php';

            if (!is_file($path)) {
                throw new \Exception('Not file find ' . $path);
            }

            require $path;
        }
    });

    // start application
    $app = App\Module\AppContainer::getInstance(
        new App\Module\AppModule($configFile)
    );

    // init router
    $app->run();

} catch (\Exception $e) {
    throw $e;
}