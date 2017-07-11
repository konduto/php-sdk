<?php namespace Konduto\Models;

class FlightLeg extends TravelLeg {

    /**
     * @inheritdoc
     */
    protected function fields() {
        return array_merge(parent::fields(), array("origin_airport", "destination_airport"));
    }

    public function getOriginAirport() {
        return $this->get("origin_airport");
    }

    public function setOriginAirport($value) {
        return $this->set("origin_airport", $value);
    }

    public function getDestinationAirport() {
        return $this->get("destination_airport");
    }

    public function setDestinationAirport($value) {
        return $this->set("destination_airport", $value);
    }
}
