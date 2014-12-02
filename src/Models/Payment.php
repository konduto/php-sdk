<?php namespace Konduto\Models;

const PAYMENT_APPROVED = "approved";
const PAYMENT_DECLINED = "declined";
const PAYMENT_PENDING  = "pending";

abstract class Payment extends Model {

    const TYPE_CARD = "credit";
    const TYPE_BOLETO = "boleto";

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