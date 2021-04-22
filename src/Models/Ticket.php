<?php namespace Konduto\Models;

use Konduto\Parsers\ModelParser;

class Ticket extends BaseModel {

    /**
     * @inheritdoc
     */
    protected function fields() {
        return array("id", "category", "section",
            "premium", "attendee");
    }

    /**
     * @inheritdoc
     */
    protected function initParsers() {
        return array(
            "attendee" => new ModelParser('Konduto\Models\Attendee')
        );
    }

    public function getId() {
        return $this->get("id");
    }

    public function setId($id) {
        return $this->set("id", $id);
    }

    public function getCategory() {
        return $this->get("category");
    }

    public function setCategory($category) {
        return $this->set("category", $category);
    }

    public function getSection() {
        return $this->get("section");
    }

    public function setSection($section) {
        return $this->set("section", $section);
    }

    public function getPremium() {
        return $this->get("premium");
    }

    public function setPremium($premium) {
        return $this->set("premium", $premium);
    }

    public function getAttendee() {
        return $this->get("attendee");
    }

    public function setAttendee($attendee) {
        return $this->set("attendee", $attendee);
    }
}
