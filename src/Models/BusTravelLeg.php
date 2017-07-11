<?php namespace Konduto\Models;

class BusTravelLeg extends TravelLeg {

    /**
     * @inheritdoc
     */
    protected function fields() {
        return array_merge(parent::fields(), array("origin_city", "destination_city"));
    }

    public function getOriginCity() {
        return $this->get("origin_city");
    }

    public function setOriginCity($value) {
        return $this->set("origin_city", $value);
    }

    public function getDestinationCity() {
        return $this->get("destination_city");
    }

    public function setDestinationCity($value) {
        return $this->set("destination_city", $value);
    }
}
