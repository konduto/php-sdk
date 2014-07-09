<?php namespace Konduto\Models;

abstract class Payment extends Model {

    const TYPE_CARD = 'credit';

    protected $type_;

    protected $AVAILABLE_TYPES = [self::TYPE_CARD];

    // Methods

    public function get_type() {
        return $this->type_;
    }

    protected function set_property(&$field, $field_name, $value) {}
    

    public function set_type($type) {}

    public function set() {}
}