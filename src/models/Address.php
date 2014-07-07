<?php
namespace konduto\models;
require_once "Geolocation.php";

class Address extends Geolocation {

    // Settable/gettable properties

    private $name_;
    private $address1_;
    private $address2_;
    private $zip_;

    // Internal properties

    protected static $MANDATORY_FIELDS = [];

    // Methods
    
    public function set() {
        // For understanding how this constructor works, take a look in Order constructor.
        $args = (func_num_args() == 1 ? func_get_arg(0) : func_get_args());

        foreach ($args as $key => $arg) {
            switch ((string) $key) {
                case '0':
                case 'name':
                    $this->name($arg);
                    break;
                case '1':
                case 'address1':
                    $this->address1($arg);
                    break;
                case '2':
                case 'address2':
                    $this->address2($arg);
                    break;
                case '3':
                case 'city':
                    $this->city($arg);
                    break;
                case '4':
                case 'state':
                    $this->state($arg);
                    break;
                case '5':
                case 'zip':
                    $this->zip($arg);
                    break;
                case '6':
                case 'country':
                    $this->country($arg);
                    break;
            }
        }
    }

    // Getters/setters
    
    public function name($name = null) {
        return isset($name) ?
            $this->set_property($this->name_, 'name', $name)
            : $this->name_;
    }

    public function address1($address1 = null) {
        return isset($address1) ? 
            $this->set_property($this->address1_, 'address', $address1)
            : $this->address1_;
    }

    public function address2($address2 = null) {
        return isset($address2) ? 
            $this->set_property($this->address2_, 'address', $address2)
            : $this->address2_;
    }

    public function zip($zip = null) {
        return isset($zip) ? 
            $this->set_property($this->zip_, 'zip', $zip)
            : $this->zip_;
    }

    public function as_array() {
        $array = [
            'name'     => $this->name_,
            'address1' => $this->address1_,
            'address2' => $this->address2_,
            'city'     => $this->city_,
            'state'    => $this->state_,
            'zip'      => $this->zip_,
            'country'  => $this->country_
        ];

        foreach ($array as $key => $value) {
            if ($value === null) {
                unset($array[$key]);
            }
        }

        return $array;
    }
}