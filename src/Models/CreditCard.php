<?php namespace Konduto\Models;

use Konduto\Parsers\DateTimeParser;

class CreditCard extends Payment {

    /**
     * @inheritdoc
     */
    public function __construct(array $args) {
        parent::__construct($args);
        $this->setType(self::TYPE_CREDIT);
    }

    /**
     * @inheritdoc
     */
    protected function fields() {
        return array_merge(parent::fields(), array("bin", "last4", "expiration_date"));
    }

    /**
     * @inheritDoc
     */
    protected function initParsers() {
        return array("expiration_date" => new DateTimeParser('mY'));
    }

    public function getBin() {
        return $this->get("bin");
    }

    public function setBin($value) {
        $this->set("bin", $value);
    }

    public function getLast4() {
        return $this->get("last4");
    }

    public function setLast4($value) {
        $this->set("last4", $value);
    }

    public function getExpirationDate() {
        return $this->get("expiration_date");
    }

    public function setExpirationDate($value) {
        $this->set("expiration_date", $value);
    }
}
