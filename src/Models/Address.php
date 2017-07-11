<?php namespace Konduto\Models;

class Address extends Geolocation {

    /**
     * @inheritdoc
     */
    protected function fields() {
        return array_merge(parent::fields(), array("name", "address1", "address2", "zip"));
    }

    public function getName() {
        return $this->get("name");
    }

    public function getAddress1() {
        return $this->get("address1");
    }

    public function getAddress2() {
        return $this->get("address2");
    }

    public function getZip() {
        return $this->get("zip");
    }

    public function setName($value) {
        return $this->set("name", $value);
    }

    public function setAddress1($value) {
        return $this->set("address1", $value);
    }

    public function setAddress2($value) {
        return $this->set("address2", $value);
    }

    public function setZip($value) {
        return $this->set("zip", $value);
    }
}
