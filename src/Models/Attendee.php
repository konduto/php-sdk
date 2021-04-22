<?php namespace Konduto\Models;

use Konduto\Parsers\DateParser;

class Attendee extends BaseModel {
    /**
     * @inheritdoc
     */
    protected function fields() {
        return array("name", "document",
            "document_type", "dob");
    }

    /**
     * @inheritdoc
     */
    protected function initParsers() {
        return array(
            "dob" => new DateParser()
        );
    }

    public function getName() {
        return $this->get("name");
    }

    public function setName($name) {
        return $this->set("name", $name);
    }

    public function getDocument() {
        return $this->get("document");
    }

    public function setDocument($document) {
        return $this->set("document", $document);
    }

    public function getDocumentType() {
        return $this->get("document_type");
    }

    public function setDocumentType($document_type) {
        return $this->set("document_type", $document_type);
    }

    /**
     * @return \DateTime
     */
    public function getDob() {
        return $this->get("dob");
    }

    public function setDob($dob) {
        return $this->set("dob", $dob);
    }
}
