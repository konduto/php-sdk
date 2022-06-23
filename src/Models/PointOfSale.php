<?php namespace Konduto\Models;

class PointOfSale extends Geolocation {

    /**
     * @inheritdoc
     */
    protected function fields() {
        return array_merge(parent::fields(), array("id", "name", "lat", "lon", "address", "zip"));
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

    public function getLat() {
        return $this->get("lat");
    }

    public function setLat($value) {
        return $this->set("lat", $value);
    }

    public function getLon() {
        return $this->get("lon");
    }

    public function setLon($value) {
        return $this->set("lon", $value);
    }

    public function getAddress() {
        return $this->get("address");
    }

    public function setAddress($value) {
        return $this->set("address", $value);
    }

    public function getZip() {
        return $this->get("zip");
    }

    public function setZip($value) {
        return $this->set("zip", $value);
    }
}