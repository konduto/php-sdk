<?php namespace Konduto\Models;

use Konduto\Parsers\DateParser;

class Customer extends BaseModel {

    /**
     * @inheritdoc
     */
    protected function fields() {
        return array("id", "name", "tax_id", "phone1", "phone2",
            "email", "new", "vip", "dob", "created_at", "document_type");
    }

    /**
     * @inheritDoc
     */
    protected function initParsers() {
        return array(
            "dob" => new DateParser(),
            "created_at" => new DateParser()
        );
    }

    public function getId() {
        return $this->get("id");
    }

    public function setId($id) {
        $this->set("id", $id);
        return $this;
    }

    public function getName() {
        return $this->get("name");
    }

    public function setName($name) {
        $this->set("name", $name);
        return $this;
    }

    public function getTaxId() {
        return $this->get("tax_id");
    }

    public function setTaxId($tax_id) {
        $this->set("tax_id", $tax_id);
        return $this;
    }

    public function getPhone1() {
        return $this->get("phone1");
    }

    public function getPhone2() {
        return $this->get("phone2");
    }

    public function setPhone1($phone1) {
        $this->set("phone1", $phone1);
        return $this;
    }

    public function setPhone2($phone2) {
        $this->set("phone2", $phone2);
        return $this;
    }

    public function getEmail() {
        return $this->get("email");
    }

    public function setEmail($email) {
        $this->set("email", $email);
        return $this;
    }

    public function getNew() {
        return $this->get("new");
    }

    public function getVip() {
        return $this->get("vip");
    }

    /**
     * @return \DateTime
     */
    public function getDob() {
        return $this->get("dob");
    }

    public function setDob($dob) {
        $this->set("dob", $dob);
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt() {
        return $this->get("created_at");
    }

    public function setCreatedAt($created_at) {
        $this->set("created_at", $created_at);
        return $this;
    }

    public function getDocumentType() {
        return $this->get("document_type");
    }

    public function setDocumentType($documentType) {
        $this->set("document_type", $documentType);
        return $this;
    }
}
