<?php namespace Konduto\Models;

class Boleto extends Payment {

    protected $_schema_key = 'boleto';

    // Settable/gettable properties

    private $expiration_date_;

    // Internal properties

    protected static $MANDATORY_FIELDS = ['type', 'boleto'];

    // Methods

    public function __construct() {
        parent::setMandatoryFields(self::$MANDATORY_FIELDS);
        $this->type_ = Payment::TYPE_BOLETO;
        $this->set(func_num_args() == 1 ? func_get_arg(0) : func_get_args());
    }

    public function set() {
        // For understanding how this constructor works, take a look in Order constructor.
        $args = (func_num_args() == 1 ? func_get_arg(0) : func_get_args());

        foreach ($args as $key => $arg) {
            switch ((string) $key) {
                case '0':
                case 'expiration_date':
                case 'expirationDate':
                    $this->expiration_date($arg);
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