<?php
/**
 * Created by PhpStorm.
 * User: maltsev
 * Date: 19.10.2017
 * Time: 17:02
 */

namespace App\Http\Models;

use App\Module\Model;

class SendForm extends Model
{
    public $age;
    public $email;
    public $name;
    public $message;

    public function rules()
    {
        return [
            'age' => ['integer', 'isRequired|isBetween:18:60:true'],
            'email' => ['string', 'isRequired|isEmail'],
            'name' => ['string', 'isRequired'],
            'message' => ['string', 'isRequired']
        ];
    }
}