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
        $this->set("origin_city", $value);
        return $this;
    }

    public function getDestinationCity() {
        return $this->get("destination_city");
    }

    public function setDestinationCity($value) {
        $this->set("destination_city", $value);
        return $this;
    }
}
