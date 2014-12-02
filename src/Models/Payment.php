<?php namespace Konduto\Models;

const PAYMENT_APPROVED = "approved";
const PAYMENT_DECLINED = "declined";
const PAYMENT_PENDING  = "pending";

abstract class Payment extends Model {

    const TYPE_CARD = "credit";
    const TYPE_BOLETO = "boleto";

    protected $type_;

    protected $AVAILABLE_TYPES = [self::TYPE_CARD, self::TYPE_BOLETO];

    // Methods

    public function get_type() {
        return $this->type_;
    }

    public function set_type($type) {
        $this->type_ = $type;
    }

    public function set() {}
}