<?php namespace Konduto\Models;

class Seller extends BaseModel {

    /**
     * @inheritDoc
     */
    protected function fields() {
        return array("id", "name", "created_at");
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
