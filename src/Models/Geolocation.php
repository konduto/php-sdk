<?php namespace Konduto\Models;

class Geolocation extends Model {

    // Settable/gettable properties

    protected $city_;
    protected $state_;
    protected $country_;

    // Internal properties

    protected static $MANDATORY_FIELDS = [];

    // Methods

    public function __construct() {
        parent::set_mandatory_fields(self::$MANDATORY_FIELDS);
        $this->set(func_num_args() == 1 ? func_get_arg(0) : func_get_args());
    }

    // Getters/setters

    public function set() {
        $args = (func_num_args() == 1 ? func_get_arg(0) : func_get_args());

        foreach ($args as $key => $arg) {
            switch ((string) $key) {
                case '0':
                case 'city':
                    $this->city($arg);
                    break;
                case '1':
                case 'state':
                    $this->state($arg);
                    break;
                case '2':
                case 'country':
                    $this->country($arg);
                    break;
            }
        }
    }
    
    public function city($city = null) {
        return isset($city) ?
            // Set
            $this->set_property($this->city_, 'city', $city)
            // Get
            : $this->city_;
    }

    public function country($country = null) {
        return isset($country) ? 
            $this->set_property($this->country_, 'country', $country)
            : $this->country_;
    }

    public function state($state = null) {
        return isset($state) ? 
            $this->set_property($this->state_, 'state', $state)
            : $this->state_;
    }

    /**
     * Does the validation according to ValidationSchema rules. If the parameter passed is valid,
     * sets the property and returns true. Returns false otherwise.
     * @param field: the property to be set.
     * @param field_name: the name of the field as in ValidationSchema.
     * @param value: the value to be set in the property.
     */
    protected function set_property(&$field, $field_name, $value) {
        if (ValidationSchema::validateAddressField($field_name, $value)) {
            $field = $value;
            unset($this->errors[$field_name]);
            return true;
        }        
        $this->errors[$field_name] = $value;
        return false;
    }
}