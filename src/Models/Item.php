<?php namespace Konduto\Models;

class Item implements Entity {

    // Settable/gettable properties

    private $sku_;
    private $product_code_;
    private $category_;
    private $name_;
    private $description_;
    private $unit_cost_;
    private $quantity_;
    private $discount_;

    // Methods

    public function __construct() {
        $this->set(func_num_args() == 1 ? func_get_arg(0) : func_get_args());
    }

    public function set() {
        // For understanding how this constructor works, take a look in Order constructor.
        $args = (func_num_args() == 1 ? func_get_arg(0) : func_get_args());

        foreach ($args as $key => $arg) {
            switch ((string) $key) {
                case '0':
                case 'sku':
                    $this->sku($arg);
                    break;
                case '1':
                case 'product_code':
                    $this->product_code($arg);
                    break;
                case '2':
                case 'category':
                    $this->category($arg);
                    break;
                case '3':
                case 'name':
                    $this->name($arg);
                    break;
                case '4':
                case 'description':
                    $this->description($arg);
                    break;
                case '5':
                case 'unit_cost':
                    $this->unit_cost($arg);
                    break;
                case '6':
                case 'quantity':
                    $this->quantity($arg);
                    break;
                case '7':
                case 'discount':
                    $this->discount($arg);
                    break;
            }
        }
    }

    // Getters/setters
    
    public function sku($sku = null) {
        return isset($sku) ?
            $this->set_property($this->sku_, 'sku', $sku)
            : $this->sku_;
    }

    public function product_code($product_code = null) {
        return isset($product_code) ? 
            $this->set_property($this->product_code_, 'product_code', $product_code)
            : $this->product_code_;
    }

    public function category($category = null) {
        return isset($category) ? 
            $this->set_property($this->category_, 'category', $category)
            : $this->category_;
    }

    public function name($name = null) {
        return isset($name) ? 
            $this->set_property($this->name_, 'name', $name)
            : $this->name_;
    }

    public function description($description = null) {
        return isset($description) ? 
            $this->set_property($this->description_, 'description', $description)
            : $this->description_;
    }

    public function unit_cost($unit_cost = null) {
        return isset($unit_cost) ? 
            $this->set_property($this->unit_cost_, 'unit_cost', $unit_cost)
            : $this->unit_cost_;
    }

    public function quantity($quantity = null) {
        return isset($quantity) ? 
            $this->set_property($this->quantity_, 'quantity', $quantity)
            : $this->quantity_;
    }

    public function discount($discount = null) {
        return isset($discount) ? 
            $this->set_property($this->discount_, 'discount', $discount)
            : $this->discount_;
    }

    /**
     * Does the validation according to ValidationSchema rules. If the parameter passed is valid,
     * sets the property and returns true. Returns false otherwise.
     * @param field: the property to be set.
     * @param field_name: the name of the field as in ValidationSchema.
     * @param value: the value to be set in the property.
     */
    private function set_property(&$field, $field_name, $value) {
        if (ValidationSchema::validateItemField($field_name, $value)) {
            $field = $value;
            return true;
        }
        return false;
    }

    public function get_errors() {}
    public function is_valid() {
        return true;
    }

    public function as_array() {
        $array = [
            'sku'          => $this->sku_,
            'product_code' => $this->product_code_,
            'category'     => $this->category_,
            'name'         => $this->name_,
            'description'  => $this->description_,
            'unit_cost'    => $this->unit_cost_,
            'quantity'     => $this->quantity_,
            'discount'     => $this->discount_
        ];

        foreach ($array as $key => $value) {
            if ($value == null) {
                unset($array[$key]);
            }
        }

        return $array;
    }
}