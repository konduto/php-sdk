<?php namespace Konduto\Models;

class Geolocation extends BaseModel {

    protected function fields() {
        return array("city", "state", "country");
    }

    public function getCity() {
        return $this->get("city");
    }

    public function getState() {
        return $this->get("state");
    }

    public function getCountry() {
        return $this->get("country");
    }

    public function setCity($value) {
        return $this->set("city", $value);
    }

    public function setState($value) {
        return $this->set("state", $value);
    }

    public function setCountry($value) {
        return $this->set("country", $value);
    }
}
