<?php namespace Konduto\Models;

const FIELD_NOT_VALID = 'NOT_VALID';

abstract class Model implements Entity {

    protected $mandatory = [];
    protected $errors = [];

    // Method called in the constructor of child classes
    protected function set_mandatory_fields($field_array) {
        $this->mandatory = $field_array;
    }

    // Reset errors
    public function clean_errors() {
        $this->errors = null;
    }

    /**
     * Look for unpopulated mandatory fields and add them to errors property.
     */
    public function get_errors() {
        // Check if all mandatory fields are present
        $properties = $this->as_array();

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
    public function is_valid() {
        // Check if there are errors
        return empty($this->get_errors());
    }

    abstract public function set();

    /**
     * Does the validation according to ValidationSchema rules. If the parameter passed is valid,
     * sets the property and returns true. Returns false otherwise.
     * @param field: the property to be set.
     * @param field_name: the name of the field as in ValidationSchema.
     * @param value: the value to be set in the property.
     */
    abstract protected function set_property(&$field, $field_name, $value);
}