<?php namespace Konduto\Models;

use Konduto\Parsers\ArrayModelParser;

abstract class Travel extends BaseModel {

    const TYPE_BUS = "bus";
    const TYPE_FLIGHT = "flight";

    /**
     * @inheritDoc
     */
    protected function fields() {
        return array("type", "departure", "return", "passengers");
    }

    /**
     * @inheritDoc
     */
    protected function initParsers() {
        return array("passengers" => new ArrayModelParser('Konduto\Models\Passenger'));
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
        $this->set("type", $value);
        return $this;
    }

    /**
     * @return \Konduto\Models\TravelLeg
     */
    public function getDeparture() {
        return $this->get("departure");
    }

    public function setDeparture($value) {
        $this->set("departure", $value);
        return $this;
    }

    /**
     * @return \Konduto\Models\TravelLeg
     */
    public function getReturn() {
        return $this->get("return");
    }

    public function setReturn($value) {
        $this->set("return", $value);
        return $this;
    }

    /**
     * @return \Konduto\Models\Passenger[]
     */
    public function getPassengers() {
        return $this->get("passengers");
    }

    public function setPassengers(array $value) {
        $this->set("passengers", $value);
        return $this;
    }
}
