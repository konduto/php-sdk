<?php namespace Konduto\Models;

use Konduto\Parsers\DateParser;

class Seller extends BaseModel {

    /**
     * @inheritDoc
     */
    protected function fields() {
        return array("id", "name", "created_at");
    }

    /**
     * @inheritDoc
     */
    protected function initParsers() {
        return array("created_at" => new DateParser());
    }

    public function getId() {
        return $this->get("id");
    }

    public function setId($value) {
        return $this->set("id", $value);
    }

    public function getName() {
        return $this->get("name");
    }

    public function setName($value) {
        return $this->set("name", $value);
    }

    public function getCreatedAt() {
        return $this->get("created_at");
    }

    public function setCreatedAt($value) {
        return $this->set("created_at", $value);
    }


}
