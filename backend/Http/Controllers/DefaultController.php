<?php
/**
 * Created by PhpStorm.
 * User: maltsev
 * Date: 19.10.2017
 * Time: 13:40
 */

namespace App\Http\Controllers;

use App\Http\Models\SendForm;
use App\Module\Controller;

class DefaultController extends Controller
{
    /**
     * Action /
     *
     * @return void
     */
    public function index()
    {
        $model = new SendForm();
        $model->setAttributes([
            'name' => 'Вася',
            'email' => 'a@a',
            'message' => 'sdasdsa',
            'age' => 0
        ]);

        $errors = null;

        if (!$model->validate()) {
            $errors = $model->getErrors();
        }

        $this->render('site/index', [
            'name' => 'Вася',
            'errors' => $errors
        ]);
    }
}