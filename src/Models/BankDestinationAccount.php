<?php namespace Konduto\Models;

class BankDestinationAccount extends Bank {

    /**
     * @inheritdoc
     */
    protected function fields() {
        return array_merge(parent::fields(), array("amount"));
    }

    public function getAmount() {
        return $this->get("amount");
    }

    public function setAmount($value) {
        return $this->set("amount", $value);
    }
}
