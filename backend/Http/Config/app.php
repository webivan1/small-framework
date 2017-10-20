<?php
/**
 * Created by PhpStorm.
 * User: maltsev
 * Date: 19.10.2017
 * Time: 11:15
 */

return [
    'router' => [
        'class' => 'App\Module\Router',
        'rules' => require __DIR__ . DIRECTORY_SEPARATOR . 'router.php'
    ],

    'view' => [
        'class' => 'App\Module\View\View',
        'path' => APP . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR . 'views'
    ]
];