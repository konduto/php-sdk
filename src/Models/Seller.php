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
        $this->set("id", $value);
        return $this;
    }

    public function getName() {
        return $this->get("name");
    }

    public function setName($value) {
        $this->set("name", $value);
        return $this;
    }

    public function getCreatedAt() {
        return $this->get("created_at");
    }

    public function setCreatedAt($value) {
        $this->set("created_at", $value);
        return $this;
    }


}
