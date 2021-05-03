<?php namespace Konduto\Models;

use Konduto\Parsers\ModelParser;
use Konduto\Parsers\ArrayModelParser;
use Konduto\Parsers\DateTimeParser;

class Event extends BaseModel {

    /**
     * @inheritdoc
     */
    protected function fields() {
        return array("name", "date", "type",
            "subtype", "venue", "tickets");
    }

    /**
     * @inheritdoc
     */
    protected function initParsers() {
        return array(
            "date" => new DateTimeParser('Y-m-d\TH:i:s\Z'),
            "venue" => new ModelParser('Konduto\Models\Venue'),
            "tickets" => new ArrayModelParser('Konduto\Models\Ticket')
        );
    }

    public function getName() {
        return $this->get("name");
    }

    public function setName($name) {
        return $this->set("name", $name);
    }

    /**
     * @return \DateTime
     */
    public function getDate() {
        return $this->get("date");
    }

    public function setDate($date) {
        return $this->set("date", $date);
    }

    public function getType() {
        return $this->get("type");
    }

    public function setType($type) {
        return $this->set("type", $type);
    }

    public function getSubtype() {
        return $this->get("subtype");
    }

    public function setSubtype($subtype) {
        return $this->set("subtype", $subtype);
    }

    public function getVenue() {
        return $this->get("venue");
    }

    public function setVenue($venue) {
        return $this->set("venue", $venue);
    }

    public function getTickets() {
        return $this->get("tickets");
    }

    public function setTickets($tickets) {
        return $this->set("tickets", $tickets);
    }

}
