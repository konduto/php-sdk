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
        $this->set("date", $value);
        return $this;
    }

    public function getNumberOfConnections() {
        return $this->get("number_of_connections");
    }

    public function setNumberOfConnections($value) {
        $this->set("number_of_connections", $value);
        return $this;
    }

    public function getClass() {
        return $this->get("class");
    }

    public function setClass($value) {
        $this->set("class", $value);
        return $this;
    }

    public function getFareBasis() {
        return $this->get("fare_basis");
    }

    public function setFareBasis($value) {
        $this->set("fare_basis", $value);
        return $this;
    }
}
