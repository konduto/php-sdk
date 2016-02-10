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
        $this->set("name", $value);
        return $this;
    }

    public function getDocument() {
        return $this->get("document");
    }

    public function setDocument($value) {
        $this->set("document", $value);
        return $this;
    }

    public function getDocumentType() {
        return $this->get("document_type");
    }

    public function setDocumentType($value) {
        $this->set("document_type", $value);
        return $this;
    }

    public function getDob() {
        return $this->get("dob");
    }

    public function setDob($value) {
        $this->set("dob", $value);
        return $this;
    }

    public function getNationality() {
        return $this->get("nationality");
    }

    public function setNationality($value) {
        $this->set("nationality", $value);
        return $this;
    }

    public function getLoyalty() {
        return $this->get("loyalty");
    }

    public function setLoyalty($value) {
        $this->set("loyalty", $value);
        return $this;
    }

    public function getFrequentTraveler() {
        return $this->get("frequent_traveler");
    }

    public function setFrequentTraveler($value) {
        $this->set("frequent_traveler", $value);
        return $this;
    }

    public function getSpecialNeeds() {
        return $this->get("special_needs");
    }

    public function setSpecialNeeds($value) {
        $this->set("special_needs", $value);
        return $this;
    }
}
