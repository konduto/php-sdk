<?php namespace Konduto\Models;

use Konduto\Parsers\DateParser;

class AgentSeller extends BaseModel {

    /**
     * @inheritDoc
     */
    protected function fields() {
        return array("id", "login", "name", "tax_id", "dob", "category", "created_at");
    }

    /**
     * @inheritDoc
     */
    protected function initParsers() {
        return array(
            "created_at" => new DateParser(),
            "dob" => new DateParser()
        );
    }

    public function getId() {
        return $this->get("id");
    }

    public function setId($value) {
        return $this->set("id", $value);
    }


    public function getLogin() {
        return $this->get("login");
    }

    public function setLogin($value) {
        return $this->set("login", $value);
    }

    public function getName() {
        return $this->get("name");
    }

    public function setName($value) {
        return $this->set("name", $value);
    }

    public function getTaxId() {
        return $this->get("tax_id");
    }

    public function setTaxId($value) {
        return $this->set("tax_id", $value);
    }

    /**
     * YYYY-MM-DD
     * @return \DateTime
     */
    public function getDob() {
        return $this->get("dob");
    }

    public function setDob($value) {
        return $this->set("dob", $value);
    }

    public function getCategory(){
        return $this->get("category");
    }

    public function setCategory($value){
        return $this->set("category", $value);
    }

    /**
     * YYYY-MM-DD
     * @return \DateTime
     */
    public function getCreatedAt() {
        return $this->get("created_at");
    }

    public function setCreatedAt($value) {
        return $this->set("created_at", $value);
    }


}