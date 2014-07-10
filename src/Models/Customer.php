<?php namespace Konduto\Models;

class Customer extends Model {

    // Settable/gettable properties

    private $id_;
    private $name_;
    private $tax_id_;
    private $phone_array_ = [];
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
                case '4':
                case 'phone1':
                case 'phone2':
                    $this->addPhone($arg);
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

    public function phones($phone_array = null) {
        if (isset($phone_array)) {
            if (!is_array($phone_array)) {
                return null;
            }            
            foreach ($phone_array as $ph) {
                if (ValidationSchema::validateCustomerField('phone1', $ph)) {
                    $this->phone_array_[] = $ph;
                }
                else {
                    return null;
                }
            }
            return true;
        }
        else {
            return $this->phone_array_;
        }
    }

    public function addPhone($phone = null) {
        if (ValidationSchema::validateCustomerField('phone1', $phone)) {
            array_push($this->phone_array_, $phone);
        }
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

    /**
     * Does the validation according to ValidationSchema rules. If the parameter passed is valid,
     * sets the property and returns true. Returns false otherwise.
     * @param field: the property to be set.
     * @param field_name: the name of the field as in ValidationSchema.
     * @param value: the value to be set in the property.
     */
    protected function set_property(&$field, $field_name, $value) {
        if (ValidationSchema::validateCustomerField($field_name, $value)) {
            $field = $value;
            unset($this->errors[$field_name]);
            return true;
        }        
        $this->errors[$field_name] = $value;
        return false;
    }

    public function asArray() {
        $array = [
            'id'     => $this->id_,
            'name'   => $this->name_,
            'tax_id' => $this->tax_id_,
            'phone1' => isset($this->phone_array_[0]) ? $this->phone_array_[0] : null, //Porquisse
            'phone2' => isset($this->phone_array_[1]) ? $this->phone_array_[1] : null,
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