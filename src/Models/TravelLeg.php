<?php namespace Konduto\Models;

use Konduto\Parsers\DateTimeParser;

class TravelLeg extends BaseModel {

    /**
     * @inheritdoc
     */
    protected function fields() {
        return array("date", "number_of_connections", "class", "fare_basis");
    }

    /**
     * @inheritDoc
     */
    protected function initParsers() {
        return array("date" => new DateTimeParser('Y-m-d\TH:i\Z'));
    }

    public function getDate() {
        return $this->get("date");
    }

    public function setDate($value) {
        return $this->set("date", $value);
    }

    public function getNumberOfConnections() {
        return $this->get("number_of_connections");
    }

    public function setNumberOfConnections($value) {
        return $this->set("number_of_connections", $value);
    }

    public function getClass() {
        return $this->get("class");
    }

    public function setClass($value) {
        return $this->set("class", $value);
    }

    public function getFareBasis() {
        return $this->get("fare_basis");
    }

    public function setFareBasis($value) {
        return $this->set("fare_basis", $value);
    }
}
