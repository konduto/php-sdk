<?php namespace Konduto\Models;

use Konduto\Parsers\ArrayModelParser;
use Konduto\Parsers\DateTimeParser;

abstract class Travel extends BaseModel {

    const TYPE_BUS = "bus";
    const TYPE_FLIGHT = "flight";

    /**
     * @inheritDoc
     */
    protected function fields() {
        return array("type", "departure", "return", "passengers", "expiration_date");
    }

    /**
     * @inheritDoc
     */
    protected function initParsers() {
        return array(
            "passengers" => new ArrayModelParser('Konduto\Models\Passenger'),
            "expiration_date" => new DateTimeParser('Y-m-d\TH:i:s\Z')
        );
    }

    /**
     * Given an array, instantiates a travel among the possible
     * types of travel. The decision of what Model to use is made
     * by the field 'type'
     * @param array $args: array containing fields of the Travel
     * @return Travel BusTravel or Flight object
     */
    public static function build(array $args) {
        if (is_array($args) && array_key_exists("type", $args)) {
            switch ($args["type"]) {
                case Travel::TYPE_BUS:
                    return new BusTravel($args);
                case Travel::TYPE_FLIGHT:
                    return new Flight($args);
            }
        }
        throw new \InvalidArgumentException("Array must contain a valid 'type' field");
    }

    public function getType() {
        return $this->get("type");
    }

    public function setType($value) {
        return $this->set("type", $value);
    }

    /**
     * @return \Konduto\Models\TravelLeg
     */
    public function getDeparture() {
        return $this->get("departure");
    }

    public function setDeparture($value) {
        return $this->set("departure", $value);
    }

    /**
     * @return \Konduto\Models\TravelLeg
     */
    public function getReturn() {
        return $this->get("return");
    }

    public function setReturn($value) {
        return $this->set("return", $value);
    }

    /**
     * @return \Konduto\Models\Passenger[]
     */
    public function getPassengers() {
        return $this->get("passengers");
    }

    public function setPassengers(array $value) {
        return $this->set("passengers", $value);
    }

    public function getExpirationDate() {
        return $this->get("expiration_date");
    }

    public function setExpirationDate($value) {
        return $this->set("expiration_date", $value);
    }
}
