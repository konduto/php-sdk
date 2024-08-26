<?php namespace Konduto\Models;

class BankOriginAccount extends Bank {

    /**
     * @inheritdoc
     */
    protected function fields() {
        return array_merge(parent::fields(), array("balance"));
    }

    public function getBalance() {
        return $this->get("balance");
    }

    public function setBalance($value) {
        return $this->set("balance", $value);
    }

}