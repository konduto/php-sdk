<?php namespace Konduto\Models;

const FIELD_NOT_VALID = 'NOT_VALID';

abstract class Model implements Entity {

    /**
     * Associative array containing the properties of the model.
     * The fields provided are the ones used in
     * json message posted to server.
     */
    protected $_properties = [];

    /**
     * Array of fields that are mandatory when posting an order.
     */
    protected $_mandatory_fields = [];

    /**
     * Contains values of properties that haven't succeded
     * validation.
     */
    protected $_errors = [];

    /**
     * Informs the key containing the validation
     * schema for the model.
     */
    protected $_schema_key;

    /**
     * The constructor sets properties in the same
     * way as the 'set' method.
     */
    public function __construct() {
        $this->set(func_num_args() == 1 ? func_get_arg(0) : func_get_args());
    }

    /**
     * Clean all errors detected. (is_valid method will recreate them)
     */
    public function clean_errors() {
        $this->_errors = [];
    }

    /**
     * Look for unpopulated mandatory fields or invalid objects
     * and add them to _errors property.
     */
    public function get_errors() {
        $this->clean_errors();

        foreach ($this->_properties as $property => $value) {
            $this->validate_property($property);
        }

        return $this->_errors;
    }

    /**
     * Return true no validation error occurred and all
     * mandatory fields are populated.
     */
    public function is_valid() {
        $errors = $this->get_errors(); // Hack for php 5.4-
        return empty($errors);
    }

    /**
     * Overrides __call magic method: tries to see if the method's name is
     * the same as of a property in $properties array. If yes, set it (if
     * an argument is passed), or get its value (if no argument is passed).
     * @throws BadMethodCallException if methods' name is not a property.
     */
    public function __call($name, $arguments) {
        if (array_key_exists($name, $this->_properties)) {

            $name = $this->clean_name($name);

            return count($arguments) > 0 ?
                $this->set_property($name, $arguments[0]) // set
                : $this->_properties[$name];              // get
        }
        else {
            throw new \BadMethodCallException("Method '$name' ".
                                             "does not exist.");
        }
    }

    /**
     * Set one or more properties in the $_properties array.
     * It accepts a sequence of arguments in the same order
     * as the $_properties array, or as an associative array
     * with the properties' keys as values.
     */
    public function set() {
        $args = func_num_args() == 1 ? func_get_arg(0) : func_get_args();
        $properties_keys = array_keys($this->_properties);
        $class_methods = get_class_methods(get_class($this));

        foreach ($args as $key => $arg) {
            if (is_numeric($key)) {
                $key = array_search($key, $properties_keys);
            }

            // Treat the case of PHP's reserved word 'new'
            if ($key == "new") {
                $key = "is_new";
            }
            // Treat the case of PHP's reserved word 'return'
            if ($key == "return") {
                $key = "return_leg";
            }

            // Check if a method named $key exists
            if (in_array($key, $class_methods)
                 or in_array($key, $properties_keys)) {
                $this->$key($arg);
            }
        }
    }

    /**
     * Does the validation according to ValidationSchema rules.
     * If the parameter passed is valid,
     * sets the property and returns true. Returns false otherwise.
     * @param name: the name of the property.
     * @param value: the value to be set in the property.
     */
    protected function set_property($name, $value) {
        if (!empty($this->_schema_key)) {
            $value = ValidationSchema::coerce($this->_schema_key, $name, $value);
        }
        $this->_properties[$name] = $value;
        return $this->validate_property($name, $value);
    }

    /**
     * Sets or gets a property as an object of a Konduto model.
     * It is a setter if $value is passed, a getter otherwise.
     * @param property: name of the property being set or get
     * @param value: an object or array representing the object.
     * @param class: class of the object being set or get.
     */
    protected function set_get_object($property, $value, $class) {
        if (!empty($property) && !empty($class)) {
            // Getter
            if (!isset($value)) {
                return $this->_properties[$property];
            }

            // Setter
            else {
                if (is_a($value, $class)) {
                    $this->_properties[$property] = $value;
                }
                else if (is_array($value)) {
                    $this->_properties[$property] =
                        method_exists($class, "instantiate") ?
                            call_user_func([$class, "instantiate"], $value) :
                            new $class($value);
                }
                else {
                    $this->_errors[$property] = FIELD_NOT_VALID;
                    return null;
                }
            }
        }
        else {
            throw new \Konduto\Exceptions\KondutoAPIErrorException();
        }
    }

    /**
     * Just like set_get_object, but deals with setting or getting
     * an array of Models objects.
     * @param property: name of the property being set or get
     * @param array: an array of the object being set.
     * @param class: class of the object being set or get.
     */
    protected function set_get_array_object($property, $array, $class) {
        // Getter
        if (!isset($array)) {
            return $this->_properties[$property];
        }

        // Setter
        if (!is_array($array)) {
            $this->_errors[$property] = FIELD_NOT_VALID;
            return null;
        }
        foreach ($array as $object) {
            if (is_a($object, $class)) {
                $this->_properties[$property][] = $object;
            }
            else if (is_array($object)) {
                $this->_properties[$property][] =
                    method_exists($class, "instantiate") ?
                        call_user_func([$class, "instantiate"], $object) :
                        new $class($object);
            }
            else {
                $this->_errors[$property] = FIELD_NOT_VALID;
            }
        }
    }

    /**
     * Clean a property name to its equivalent as used by Konduto API.
     * (This is particularly used to avoid reserved words such as 'new')
     * @param field_name: string
     * @return api name string
     */
    protected function clean_name($field_name) {
        // deal with is_new case
        if (strpos($field_name, "is_") === 0) {
            return substr($field_name, 3);
        }
        else if ($field_name == "return_leg") {
            return "return";
        }
        return $field_name;
    }

    /**
     * Builds an array representation of this object using $_properties.
     */
    public function to_array() {
        $array = $this->_properties;

        foreach ($array as $key => $value) {
            if (empty($value)) {
                unset($array[$key]);
            }
            else if (is_array($value)) {
                foreach ($value as $sub_key => $sub_value) {
                    if (is_a($sub_value, "Konduto\Models\Model")) {
                        $array[$key][$sub_key] = $sub_value->to_array();
                    }
                }
            }
            else if (is_a($value, "Konduto\Models\Model")) {
                $array[$key] = $value->to_array();
            }
        }

        return $array;
    }

    /**
     * Validates if a property contains a valid value.
     * If the property accepts an array
     * @param prop_name
     * @return boolean
     */
    protected function validate_property($prop_name) {
        if (empty($this->_schema_key)) {
            return true;
        }

        $value = $this->_properties[$prop_name];
        $prop_in_schema = ValidationSchema::schemaHasField($this->_schema_key, $prop_name);

        if (is_array($value) && !$prop_in_schema) {
            if (count($value) == 0 && in_array($prop_name, $this->_mandatory_fields)) {
                $this->_errors[$prop_name] = FIELD_NOT_VALID;
                return false;
            }
            foreach ($value as $arr_item) {
                if (!is_a($arr_item, "Konduto\Models\Model") || !$arr_item->is_valid()) {
                    $this->_errors[$prop_name] = FIELD_NOT_VALID;
                    return false;
                }
            }
        }
        else if ($prop_in_schema && ValidationSchema::validateField($this->_schema_key,
                                                                $prop_name, $value)) {
            // Continue
        }
        else if (!$prop_in_schema && is_a($value, "Konduto\Models\Model")
                    && $value->is_valid()) {
            // Continue
        }
        else if ((is_null($value) || !isset($value))
                && !in_array($prop_name, $this->_mandatory_fields)) {
            // Continue
        }
        else {
            $this->_errors[$prop_name] = is_array($value) ? FIELD_NOT_VALID : $value;
            return false;
        }
        unset($this->_errors[$prop_name]);
        return true;
    }
}
