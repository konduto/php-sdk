<?php namespace Konduto\Models;

class Venue extends Geolocation {

    /**
     * @inheritdoc
     */
    protected function fields() {
        return array_merge(parent::fields(), array("name", "address", "capacity"));
    }

    public function getName() {
        return $this->get("name");
    }

    public function setName($name) {
        return $this->set("name", $name);
    }

    public function getAddress() {
        return $this->get("address");
    }

    public function setAddress($address) {
        return $this->set("address", $address);
    }

    public function getCapacity() {
        return $this->get("capacity");
    }

    public function setCapacity($capacity) {
        return $this->set("capacity", $capacity);
    }
}
