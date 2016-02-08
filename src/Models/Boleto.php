<?php namespace Konduto\Models;

class Boleto extends Payment {

    public function __construct(array $args) {
        parent::__construct($args);
        $this->setType(self::TYPE_BOLETO);
    }

    /**
     * @inheritdoc
     */
    protected function fields() {
        return array_merge(parent::fields(), array("expiration_date"));
    }

    public function getExpirationDate() {
        return $this->get("expiration_date");
    }

    public function setExpirationDate($value) {
        $this->set("expiration_date", $value);
    }
}
