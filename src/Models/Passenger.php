<?php namespace Konduto\Models;

use Konduto\Parsers\DateParser;
use Konduto\Parsers\ModelParser;

class Passenger extends BaseModel {

    /**
     * @inheritDoc
     */
    protected function fields() {
        return array("name", "document", "document_type", "dob",
            "nationality", "loyalty", "frequent_traveler", "special_needs");
    }

    /**
     * @inheritDoc
     */
    protected function initParsers() {
        return array(
            "dob" => new DateParser(),
            "loyalty" => new ModelParser('Konduto\Models\Loyalty')
        );
    }

    public function getName() {
        return $this->get("name");
    }

    public function setName($value) {
        return $this->set("name", $value);
    }

    public function getDocument() {
        return $this->get("document");
    }

    public function setDocument($value) {
        return $this->set("document", $value);
    }

    public function getDocumentType() {
        return $this->get("document_type");
    }

    public function setDocumentType($value) {
        return $this->set("document_type", $value);
    }

    public function getDob() {
        return $this->get("dob");
    }

    public function setDob($value) {
        return $this->set("dob", $value);
    }

    public function getNationality() {
        return $this->get("nationality");
    }

    public function setNationality($value) {
        return $this->set("nationality", $value);
    }

    public function getLoyalty() {
        return $this->get("loyalty");
    }

    public function setLoyalty($value) {
        return $this->set("loyalty", $value);
    }

    public function getFrequentTraveler() {
        return $this->get("frequent_traveler");
    }

    public function setFrequentTraveler($value) {
        return $this->set("frequent_traveler", $value);
    }

    public function getSpecialNeeds() {
        return $this->get("special_needs");
    }

    public function setSpecialNeeds($value) {
        return $this->set("special_needs", $value);
    }
}
