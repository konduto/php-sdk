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
        $this->set("program", $value);
        return $this;
    }

    public function getCategory() {
        return $this->get("category");
    }

    public function setCategory($value) {
        $this->set("category", $value);
        return $this;
    }
}
