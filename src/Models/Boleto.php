<?php namespace Konduto\Models;

class Boleto extends Payment {

    protected $_schema_key = "boleto";

    // Settable/gettable properties

    protected $expiration_date_;
    protected $status_;

    // Internal properties

    protected static $MANDATORY_FIELDS = ["type"];

    // Methods

    public function __construct() {
        parent::setMandatoryFields(self::$MANDATORY_FIELDS);
        $this->type_ = Payment::TYPE_BOLETO;
        $this->set(func_num_args() == 1 ? func_get_arg(0) : func_get_args());
    }

    public function set() {
        // For understanding how this constructor works, 
        // take a look in Order constructor.
        $args = (func_num_args() == 1 ? func_get_arg(0) : func_get_args());

        foreach ($args as $key => $arg) {
            switch ((string) $key) {
                case '0':
                case 'expiration_date':
                case 'expirationDate':
                    $this->expiration_date($arg);
                    break;
                case '1':
                case 'status':
                    $this->status($arg);
                    break;
            }
        }
    }

    // Getters/setters

    public function expiration_date($expiration_date = null) {
        return isset($expiration_date) ? 
            $this->set_property($this->expiration_date_, 'expiration_date', $expiration_date)
            : $this->expiration_date_;
    }

    public function status($status = null) {
        return isset($status) ?
            $this->set_property($this->status_, 'status', $status)
            : $this->status_;
    }


    /**
     * Alias for expiration_date()
     */
    public function expirationDate($expiration_date = null) {
        return $this->expiration_date($expiration_date);
    }

    public function asArray() {
        $array = [
            'type'            => $this->type_,
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