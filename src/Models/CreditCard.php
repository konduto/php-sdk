<?php namespace Konduto\Models;

class CreditCard extends Payment {

    // Settable/gettable properties

    private $status_;
    private $sha1_;
    private $bin_;
    private $last4_;
    private $expiration_date_;

    // Internal properties

    protected static $MANDATORY_FIELDS = ['type', 'status'];

    // Methods

    public function __construct() {
        parent::set_mandatory_fields(self::$MANDATORY_FIELDS);
        $this->type_ = Payment::TYPE_CARD;
        $this->set(func_num_args() == 1 ? func_get_arg(0) : func_get_args());
    }

    public function set() {
        // For understanding how this constructor works, take a look in Order constructor.
        $args = (func_num_args() == 1 ? func_get_arg(0) : func_get_args());

        foreach ($args as $key => $arg) {
            switch ((string) $key) {
                case '0':
                case 'status':
                    $this->status($arg);
                    break;
                case '1':
                case 'sha1':
                    $this->sha1($arg);
                    break;
                case '3':
                case 'bin':
                    $this->bin($arg);
                    break;
                case '4':
                case 'last4':
                    $this->last4($arg);
                    break;
                case '5':
                case 'expiration_date':
                    $this->expiration_date($arg);
                    break;
            }
        }
    }

    // Getters/setters
    
    public function status($status = null) {
        return isset($status) ?
            $this->set_property($this->status_, 'status', $status)
            : $this->status_;
    }

    public function sha1($sha1 = null) {
        return isset($sha1) ? 
            $this->set_property($this->sha1_, 'sha1', $sha1)
            : $this->sha1_;
    }

    public function bin($bin = null) {
        return isset($bin) ? 
            $this->set_property($this->bin_, 'bin', $bin)
            : $this->bin_;
    }

    public function last4($last4 = null) {
        return isset($last4) ? 
            $this->set_property($this->last4_, 'last4', $last4)
            : $this->last4_;
    }

    public function expiration_date($expiration_date = null) {
        return isset($expiration_date) ? 
            $this->set_property($this->expiration_date_, 'expiration_date', $expiration_date)
            : $this->expiration_date_;
    }

    /**
     * Does the validation according to ValidationSchema rules. If the parameter passed is valid,
     * sets the property and returns true. Returns false otherwise.
     * @param field: the property to be set.
     * @param field_name: the name of the field as in ValidationSchema.
     * @param value: the value to be set in the property.
     */
    protected function set_property(&$field, $field_name, $value) {
        if (ValidationSchema::validatePaymentField($field_name, $value)) {
            $field = $value;
            unset($this->errors[$field_name]);
            return true;
        }        
        $this->errors[$field_name] = $value;
        return false;
    }

    public function as_array() {
        $array = [
            'type'            => $this->type_,
            'status'          => $this->status_,
            'sha1'            => $this->sha1_,
            'bin'             => $this->bin_,
            'expiration_date' => $this->expiration_date_
        ];

        foreach ($array as $key => $value) {
            if ($value === null) {
                unset($array[$key]);
            }
        }

        return $array;
    }
}