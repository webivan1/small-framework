<?php
/**
 * Created by PhpStorm.
 * User: maltsev
 * Date: 19.10.2017
 * Time: 14:05
 */

namespace App\Module\View;

use App\Helpers\Text;

class TwigFunctions extends \Twig_Extension
{
    /**
     * Config new functions twig
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('declensionWords', [$this, 'getDeclensionWords']),
            new \Twig_SimpleFunction('shortPrice', [$this, 'getShortPrice']),
        ];
    }

    /**
     * Handler function
     *
     * @param int $num
     * @param array $declension
     * @return string
     */
    public function getDeclensionWords($num, $declension)
    {
        return Text::declensionWords($num, $declension);
    }

    /**
     * Handler function
     *
     * @param int $price
     * @return array
     */
    public function getShortPrice($price)
    {
        return Text::shortPrice($price);
    }
}