<?php namespace Konduto\Models;

class Loyalty extends BaseModel {

    /**
     * @inheritdoc
     */
    protected function fields() {
        return array("program", "category");
    }

    public function getProgram() {
        return $this->get("program");
    }

    public function setProgram($value) {
        return $this->set("program", $value);
    }

    public function getCategory() {
        return $this->get("category");
    }

    public function setCategory($value) {
        return $this->set("category", $value);
    }
}
