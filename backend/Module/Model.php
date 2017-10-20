<?php
/**
 * Created by PhpStorm.
 * User: maltsev
 * Date: 19.10.2017
 * Time: 17:15
 */

namespace App\Module;

use NilPortugues\Validator\ValidatorFactory;

class Model
{
    protected $attributes = [];
    protected $errors = [];

    public function __construct()
    {
        $this->getBaseProperty();
    }

    protected function getBaseProperty()
    {
        $this->attributes = [];

        $props = (new \ReflectionClass($this))->getProperties(\ReflectionProperty::IS_PUBLIC);
        $callModelName = get_called_class();

        foreach ($props as $prop) {
            if ($prop->class === $callModelName) {
                $this->attributes[$prop->name] = $this->{$prop->name};
            }
        }

        return $this->attributes;
    }

    public function setAttributes(array $data = [])
    {
        foreach ($data as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }

    public function setAttribute($name, $value)
    {
        if ($this->hasAttribute($name)) {
            $this->{$name} = $value;
        }
    }

    public function hasAttribute($name)
    {
        return array_key_exists($name, $this->attributes);
    }

    public function getAttribute($name)
    {
        return $this->hasAttribute($name) ? $this->{$name} : null;
    }

    public function getAttributes()
    {
        return $this->getBaseProperty();
    }

    public function getAttributeLabels()
    {
        return array_combine(
            array_keys($this->attributes),
            array_map('ucfirst', array_keys($this->attributes))
        );
    }

    public function addError($attr, $message)
    {
        $this->errors[$attr] = $message;
    }

    public function hasErrors()
    {
        return !empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function rules()
    {
        return [];
    }

    public function validate()
    {
        $labels = $this->getAttributeLabels();

        foreach ($this->rules() as $attribute => $validateRules) {
            $validator = ValidatorFactory::create(
                !empty($labels[$attribute]) ? $labels[$attribute] : ucfirst($attribute),
                $validateRules[0]
            );

            if (!$this->isValidValue($validator, $this->{$attribute}, $validateRules[1])) {
                $this->addError($attribute, array_values(array_shift($validator->getErrors())));
            }
        }

        return !$this->hasErrors();
    }

    private function isValidValue(&$validator, $value, $rules)
    {
        foreach (explode('|', $rules) as $rule) {
            $params = explode(':', $rule);
            $validatorMethod = array_shift($params);

            if (method_exists($validator, $validatorMethod)) {
                call_user_func_array([$validator, $validatorMethod], $params);
            }
        }

        return $validator->validate($value);
    }
}