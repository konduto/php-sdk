<?php namespace Konduto\Models;

class Customer extends Model {

    protected $_schema_key = 'customer';

    // Settable/gettable properties

    private $id_;
    private $name_;
    private $tax_id_;
    private $phone1_;
    private $phone2_;
    private $email_;
    private $new_;
    private $vip_;

    // Internal properties

    protected static $MANDATORY_FIELDS = ['id', 'name', 'email'];

    // Methods

    public function __construct() {
        parent::setMandatoryFields(self::$MANDATORY_FIELDS);
        $this->set(func_num_args() == 1 ? func_get_arg(0) : func_get_args());
    }

    // Getters/setters

    public function set() {
        // If just one parameter is passed, it should be an array with properties to be populated.
        // If more than one parameter is passed, retrieve array of args.
        // In the end, parameters should be treated as array.
        $args = (func_num_args() == 1 ? func_get_arg(0) : func_get_args());

        foreach ($args as $key => $arg) {
            switch ((string) $key) {
                case '0':
                case 'id':
                    $this->id($arg);
                    break;
                case '1':
                case 'name':
                    $this->name($arg);
                    break;
                case '2':
                case 'tax_id':
                case 'taxId':
                    $this->taxId($arg);
                    break;
                case '3':
                case 'phone1':
                    $this->phone1($arg);
                    break;
                case '4':
                case 'phone2':
                    $this->phone2($arg);
                    break;
                case '5':
                case 'email':
                    $this->email($arg);
                    break;
                case '6':
                case 'new':
                case 'isNew':
                    $this->isNew($arg);
                    break;
                case '7':
                case 'vip':
                case 'isVip':
                    $this->isVip($arg);
                    break;
            }
        }    
    }
    
    public function id($id = null) {
        return isset($id) ?
            // Set
            $this->set_property($this->id_, 'id', $id)
            // Get
            : $this->id_;
    }

    public function name($name = null) {
        return isset($name) ? 
            $this->set_property($this->name_, 'name', $name)
            : $this->name_;
    }

    public function tax_id($tax_id = null) {
        return isset($tax_id) ? 
            $this->set_property($this->tax_id_, 'tax_id', $tax_id) 
            : $this->tax_id_;
    }

    /**
     * Alias for tax_id()
     */
    public function taxId($tax_id = null) {
        return $this->tax_id($tax_id);
    }

    public function phone1($phone1 = null) {
        return isset($phone1) ?
            $this->set_property($this->phone1_, 'phone1', $phone1)
            : $this->phone1_;
    }

    public function phone2($phone2 = null) {
        return isset($phone2) ?
            $this->set_property($this->phone2_, 'phone2', $phone2)
            : $this->phone2_;
    }

    public function email($email = null) {
        return isset($email) ?
            $this->set_property($this->email_, 'email', $email)
            : $this->email_;
    }

    public function isNew($new = null) {
        return isset($new) ?
            $this->set_property($this->new_, 'new', $new)
            : $this->new_;
    }

    public function isVip($vip = null) {
        return isset($vip) ?
            $this->set_property($this->vip_, 'vip', $vip)
            : $this->vip_;
    }

    public function asArray() {
        $array = [
            'id'     => $this->id_,
            'name'   => $this->name_,
            'tax_id' => $this->tax_id_,
            'phone1' => $this->phone1_,
            'phone2' => $this->phone2_,
            'email'  => $this->email_,
            'new'    => $this->new_,
            'vip'    => $this->vip_,
        ];

        foreach ($array as $key => $value) {
            if ($value === null) {
                unset($array[$key]);
            }
        }

        return $array;
    }

}