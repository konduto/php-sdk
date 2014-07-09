<?php namespace Konduto\Models;

const STATUS_PENDING  = 'pending';
const STATUS_APPROVED = 'approved';
const STATUS_DECLINED = 'declined';
const STATUS_FRAUD    = 'fraud';

const RECOMMENDATION_APPROVE = 'approve';
const RECOMMENDATION_DECLINE = 'decline';
const RECOMMENDATION_REVIEW  = 'review';

class Order extends Model {

    // Settable/gettable properties

    private $id_;
    private $visitor_;
    private $total_amount_;
    private $shipping_amount_;
    private $tax_amount_;
    private $currency_;
    private $installments_;
    private $ip_;
    private $payment_array_ = [];
    private $customer_;
    private $billing_address_;
    private $shipping_address_;
    private $shopping_cart_ = [];

    // Gettable-only properties
    
    private $timestamp_;
    private $status_;
    private $device_;
    private $geolocation_;
    private $recommendation_;
    private $score_;
    
    // Internal properties

    protected static $MANDATORY_FIELDS = ['id', 'total_amount', 'customer'];
    protected static $AVAILABLE_STATUS = [STATUS_PENDING, STATUS_APPROVED, STATUS_DECLINED, STATUS_FRAUD];
    
    // Methods

    public function __construct() {
        // Sets mandatory fields in the parent class, so parent's static methods can access properties.
        parent::set_mandatory_fields(self::$MANDATORY_FIELDS);
        // Sets properties according to arguments passed to the constructor.
        $this->set(func_num_args() == 1 ? func_get_arg(0) : func_get_args());
    }

    /**
     * Set properties given an associative array as argument with the names of the properties as keys, 
     * or set properties with many arguments in the order of the parameters presented in Konduto API documentation.
     * If just one parameter is passed, it should be an array with properties to be populated.
     * If more than one parameter is passed, retrieve array of args.
     */
    public function set() {
        $args = (func_num_args() == 1 ? func_get_arg(0) : func_get_args());

        foreach ($args as $key => $arg) {

            // key is converted to string to let switch deal with both cases of array described above.
            switch ((string) $key) {
                case 'id':
                case '0':
                    $this->id($arg);
                    break;
                case '1':
                case 'visitor':
                    $this->visitor($arg);
                    break;
                case "total_amount":
                case '2':
                    $this->total_amount($arg);
                    break;
                case '3':
                case 'shipping_amount':
                    $this->shipping_amount($arg);
                    break;
                case '4':
                case 'tax_amount':
                    $this->tax_amount($arg);
                    break;
                case '5':
                case 'currency':
                    $this->currency($arg);
                    break;
                case '6':
                case 'installments':
                    $this->installments($arg);
                    break;
                case '7':
                case 'ip':
                    $this->ip($arg);
                    break;
                case '8':
                case 'payment_array':
                    $this->payment_array($arg);
                    break;
                case '9':
                case 'customer':
                    $this->customer($arg);
                    break;
                case '10':
                case 'billing_address':
                    $this->billing_address($arg);
                    break;
                case '11':
                case 'shipping_address':
                    $this->shipping_address($arg);
                    break;
                case '12':
                case 'shopping_cart':
                    $this->shopping_cart($arg);
                    break;
                ## properties below are not settable by other way.
                case 'device':
                    $this->device_ = new Device($arg);
                    break;
                case 'status':
                    $this->status_ = strtolower($arg);
                    break;
                case 'score':
                    $this->score_ = $arg;
                    break;
                case 'recommendation':
                    $this->recommendation_ = strtolower($arg);
                    break;
                case 'geolocation':
                    $this->geolocation_ = new Geolocation($arg);
                    break;
                case 'timestamp':
                    $this->timestamp_ = $arg;
                    break;
            }
        }
    }

    /* 
     * Regular getter/setters. If a parameter is not passed, treats method as getter.
     * Passes argument through validation if setting.
     */
    
    public function id($id = null) {
        return isset($id) ?
            // Set
            $this->set_property($this->id_, 'id', $id)
            // Get
            : $this->id_;
    }

    public function visitor($visitor = null) {
        return isset($visitor) ? 
            $this->set_property($this->visitor_, 'visitor', $visitor)
            : $this->visitor_;
    }

    public function total_amount($total_amount = null) {
        return isset($total_amount) ? 
            $this->set_property($this->total_amount_, 'total_amount', $total_amount) 
            : $this->total_amount_;
    }

    public function tax_amount($tax_amount = null) {
        return isset($tax_amount) ?
            $this->set_property($this->tax_amount_, 'tax_amount', $tax_amount)
            : $this->tax_amount_;
    }

    public function shipping_amount($shipping_amount = null) {
        return isset($shipping_amount) ?
            $this->set_property($this->shipping_amount_, 'shipping_amount', $shipping_amount)
            : $this->shipping_amount_;
    }

    public function installments($installments = null) {
        return isset($installments) ?
            $this->set_property($this->installments_, 'installments', $installments)
            : $this->installments_;
    }

    public function ip($ip = null) {
        if (!isset($ip)) {
            return $this->ip_;
        }
        else if (ip2long($ip)) {
            $this->set_property($this->ip_, 'ip', $ip);
        }
        else {
            $this->errors['ip'] = $ip;
        }
    }

    public function currency($currency = null) {
        return isset($currency) ?
            $this->set_property($this->currency_, 'currency', $currency)
            : $this->currency_;
    }

    public function payment_array(array $payment_array = null) {
        if (!isset($payment_array)) {
            return $this->payment_array_;
        }
        else {
            foreach ($payment_array as $key => $payment) {
                if (!is_a($payment, 'Konduto\Models\Payment')) {
                    return null;
                }
            }
            $this->payment_array_ = $payment_array;
            return true;
        }
    }

    public function customer(\Konduto\Models\Customer $customer = null) {
        if (!isset($customer)) {
            return $this->customer_;
        }
        else {
            $this->customer_ = $customer;
            return true;
        }
    }

    public function billing_address(\Konduto\Models\Address $address = null) {
        if (!isset($address)) {
            return $this->billing_address_;
        }
        else {
            $this->billing_address_ = $address;
            return true;
        }
    }

    public function shipping_address(\Konduto\Models\Address $address = null) {
        if (!isset($address)) {
            return $this->shipping_address_;
        }
        else {
            $this->shipping_address_ = $address;
            return true;
        }
    }

    public function shopping_cart(array $item_array = null) {
        if (!isset($item_array)) {
            return $this->shopping_cart_;
        }
        else {
            foreach ($item_array as $key => $item) {
                if (!is_a($item, 'Konduto\Models\Item')) {
                    return null;
                }
            }
            $this->shopping_cart_ = $item_array;
            return true;
        }
    }

    public function add_item(\Konduto\Models\Item $item) {
        $this->shopping_cart_[] = $item;
        return true;
    }

    public function add_payment(\Konduto\Models\CreditCard $cc) {
        $this->payment_array_[] = $cc;
        return true;
    }

    public function status() {
        return $this->status_;
    }

    public function timestamp() {
        return $this->timestamp_;
    }

    public function device() {
        return $this->device_;
    }

    public function geolocation() {
        return $this->geolocation_;
    }

    public function recommendation() {
        return $this->recommendation_;
    }

    /**
     * Does the validation according to ValidationSchema rules. If the parameter passed is valid,
     * sets the property and returns true. Returns false otherwise.
     * @param field: the property to be set.
     * @param field_name: the name of the field as in ValidationSchema.
     * @param value: the value to be set in the property.
     */
    protected function set_property(&$field, $field_name, $value) {
        if (ValidationSchema::validateOrderField($field_name, $value)) {
            $field = $value;
            unset($this->errors[$field_name]);
            return true;
        }
        $this->errors[$field_name] = $value;
        return false;
    }

    /**
     * Builds an array representation of this model. Includes only the fields that are needed for persisting
     * order (building json message in POST operation).
     */
    public function as_array() {
        $array = [
            'id'              => $this->id_,
            'visitor'         => $this->visitor_,
            'total_amount'    => $this->total_amount_,
            'shipping_amount' => $this->shipping_amount_,
            'tax_amount'      => $this->tax_amount_,
            'currency'        => $this->currency_,
            'installments'    => $this->installments_,
            'ip'              => $this->ip_
        ];

        if (isset($this->customer_)) {
            if ($this->customer_->is_valid()) {
                $array['customer'] = $this->customer_->as_array();
                unset($this->errors['customer']);
            }
            else {
                $this->errors['customer'] = FIELD_NOT_VALID;
            }
        }

        if (isset($this->billing_address_)) {
            if ($this->billing_address_->is_valid()) {
                $array['billing'] = $this->billing_address_->as_array();
                unset($this->errors['billing_address']);
            }
            else {
                $this->errors['billing_address'] = FIELD_NOT_VALID;
            }
        }

        if (isset($this->shipping_address_)) {
            if ($this->shipping_address_->is_valid()) {
                $array['shipping'] = $this->shipping_address_->as_array();
                unset($this->errors['shipping_address']);
            }
            else {
                $this->errors['shipping_address'] = FIELD_NOT_VALID;
            }
        }

        foreach ($this->payment_array_ as $payment) {
            if ($payment->is_valid()) {
                $array['payment'][] = $payment->as_array();
                unset($this->errors['payment_array']);
            }
            else {
                $this->errors['payment_array'] = FIELD_NOT_VALID;
            }
        }

        foreach ($this->shopping_cart_ as $item) {
            if ($item->is_valid()) {
                $array['shopping_cart'][] = $item->as_array();
                unset($this->errors['shopping_cart']);
            }
            else {
                $this->errors['shopping_cart'] = FIELD_NOT_VALID;
            }
        }

        foreach ($array as $key => $value) {
            if ($value === null) {
                unset($array[$key]);
            }
        }

        return $array;
    }
}