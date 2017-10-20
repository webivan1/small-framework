<?php
/**
 * Created by PhpStorm.
 * User: maltsev
 * Date: 19.10.2017
 * Time: 13:46
 */

namespace App\Module;

use App\Module\AppContainer;

class Controller
{
    public function render($template, array $params = [])
    {
        echo AppContainer::getInstance()->getView()->render($template . '.html.twig', $params);
    }
}