<?php
/**
 * Created by PhpStorm.
 * User: maltsev
 * Date: 19.10.2017
 * Time: 13:50
 */

namespace App\Module\View;

use App\Module\AppModule;
use App\Module\Component;

class View extends Component
{
    /**
     * Path views
     *
     * @property string
     */
    protected $path;

    /**
     * @property \Twig_Environment
     */
    protected $view;

    /**
     * class name functions twig
     *
     * @property string
     */
    protected $twigClassFunctions = 'App\Module\View\TwigFunctions';

    /**
     * @inheritdoc
     */
    public function __construct(array $params)
    {
        parent::__construct($params);

        if (!$this->path) {
            throw new \Exception("Undefined param path view dirrectory");
        }

        $this->view = $this->setToolsTwig();

        if ($this->twigClassFunctions) {
            $className = $this->twigClassFunctions;
            $this->view->addExtension(new $className);
        }
    }

    /**
     * @return string
     */
    public function getViewPath()
    {
        return $this->path;
    }

    /**
     * Tools twig templater
     *
     * @return \Twig_Environment
     */
    private function setToolsTwig()
    {
        $loader = new \Twig_Loader_Filesystem($this->getViewPath());

        $config = [];

        if (APP_ENV === 'dev') {
            $config = [
                'debug' => true
            ];
        } else {
            $config = [
                'cache' => APP . DIRECTORY_SEPARATOR . 'runtime' . DIRECTORY_SEPARATOR . 'views'
            ];
        }

        $twig = new \Twig_Environment($loader, $config);

        return $twig;
    }

    /**
     * Render
     *
     * @param string $template
     * @param array $params
     * @return string
     */
    public function render($template, array $params)
    {
        return $this->view->render($template, $params);
    }
}