<?php
/**
 * Created by PhpStorm.
 * User: maltsev
 * Date: 19.10.2017
 * Time: 11:12
 */

return function (\Bramus\Router\Router $router) {

    $router->setNamespace('App\Http\Controllers');

    $router->get('/', 'DefaultController@index');
    $router->post('/send-form', 'SendController@index');
};