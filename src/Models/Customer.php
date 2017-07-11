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
        return $this->set("id", $id);
    }

    public function getName() {
        return $this->get("name");
    }

    public function setName($name) {
        return $this->set("name", $name);
    }

    public function getTaxId() {
        return $this->get("tax_id");
    }

    public function setTaxId($tax_id) {
        return $this->set("tax_id", $tax_id);
    }

    public function getPhone1() {
        return $this->get("phone1");
    }

    public function getPhone2() {
        return $this->get("phone2");
    }

    public function setPhone1($phone1) {
        return $this->set("phone1", $phone1);
    }

    public function setPhone2($phone2) {
        return $this->set("phone2", $phone2);
    }

    public function getEmail() {
        return $this->get("email");
    }

    public function setEmail($email) {
        return $this->set("email", $email);
    }

    public function getNew() {
        return $this->get("new");
    }

    public function getVip() {
        return $this->get("vip");
    }

    public function setNew($new) {
        return $this->set("new", $new);
    }

    public function setVip($vip) {
        return $this->set("vip", $vip);
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

    /**
     * @return \DateTime
     */
    public function getCreatedAt() {
        return $this->get("created_at");
    }

    public function setCreatedAt($created_at) {
        return $this->set("created_at", $created_at);
    }

    public function getDocumentType() {
        return $this->get("document_type");
    }

    public function setDocumentType($documentType) {
        return $this->set("document_type", $documentType);
    }
}
