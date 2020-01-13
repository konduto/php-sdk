<?php namespace Konduto\Models;

class TriggeredRule extends BaseModel {

    /**
     * @inheritdoc
     */
    protected function fields() {
        return array("name", "decision");
    }

    public function getName() {
        return $this->get("name");
    }

    public function setName($name) {
        return $this->set("name", $name);
    }

    public function getDecision() {
        return $this->get("decision");
    }

    public function setDecision($decision) {
        return $this->set("decision", $decision);
    }
}
