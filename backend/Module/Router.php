<?php
/**
 * Created by PhpStorm.
 * User: maltsev
 * Date: 19.10.2017
 * Time: 13:31
 */

namespace App\Module;

use Bramus\Router\Router as Base;

class Router extends Component
{
    protected $rules;

    /**
     * @inheritdoc
     */
    public function __construct(array $params)
    {
        parent::__construct($params);

        $router = new Base();

        if ($this->rules) {
            call_user_func_array($this->rules, [&$router]);
            $router->run();
        }
    }
}