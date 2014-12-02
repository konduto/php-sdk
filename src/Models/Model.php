<?php namespace Konduto\Models;

const FIELD_NOT_VALID = 'NOT_VALID';

abstract class Model implements Entity {

    protected $mandatory = [];
    protected $errors = [];
    protected $_schema_key;

    // Method called in the constructor of child classes
    protected function setMandatoryFields($field_array) {
        $this->mandatory = $field_array;
    }

    // Reset errors
    public function cleanErrors() {
        $this->errors = null;
    }

    /**
     * Look for unpopulated mandatory fields and add them to errors property.
     */
    public function getErrors() {
        // Check if all mandatory fields are present
        $properties = $this->asArray();

        foreach ($this->mandatory as $field) {
            if ((!array_key_exists($field, $properties) or $properties[$field] === null) and (!isset($this->errors[$field]))) {
                $this->errors[$field] = null;
            }
        }

        return $this->errors;
    }

    /**
     * Return true no validation error occurred and all mandatory fields are populated.
     */
    public function isValid() {
        // Check if there are errors
        return empty($this->getErrors());
    }

    /**
     * Set one or more properties using an array or multiple params.
     * Look at Order model to more details.
     */
    abstract public function set();

    /**
     * Does the validation according to ValidationSchema rules. If the parameter passed is valid,
     * sets the property and returns true. Returns false otherwise.
     * @param field: the property to be set.
     * @param field_name: the name of the field as in ValidationSchema.
     * @param value: the value to be set in the property.
     */
    // abstract protected function set_property(&$field, $field_name, $value);

        /**
     * Does the validation according to ValidationSchema rules. If the parameter passed is valid,
     * sets the property and returns true. Returns false otherwise.
     * @param field: the property to be set.
     * @param field_name: the name of the field as in ValidationSchema.
     * @param value: the value to be set in the property.
     */
    protected function set_property(&$field, $field_name, $value) {
        if (empty($this->_schema_key)) {
            $field = $value;
            return true;
        }
        else {
            if (ValidationSchema::validateField($this->_schema_key, 
                                    $field_name, $value)) {
                $field = $value;
                unset($this->errors[$field_name]);
                return true;
            }
            $this->errors[$field_name] = $value;
        }
        return false;
    }
}