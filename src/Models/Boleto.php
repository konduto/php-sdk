<?php namespace Konduto\Models;

use Konduto\Parsers\DateTimeParser;

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

    /**
     * @inheritDoc
     */
    protected function initParsers() {
        return array("expiration_date" => new DateTimeParser('Y-m-d'));
    }


    public function getExpirationDate() {
        return $this->get("expiration_date");
    }

    public function setExpirationDate($value) {
        $this->set("expiration_date", $value);
    }
}
