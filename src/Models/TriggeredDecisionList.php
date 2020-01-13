<?php namespace Konduto\Models;

class TriggeredDecisionList extends BaseModel {

    /**
     * @inheritdoc
     */
    protected function fields() {
        return array("trigger", "type", "decision");
    }

    public function getTrigger() {
        return $this->get("trigger");
    }

    public function setTrigger($trigger) {
        return $this->set("trigger", $trigger);
    }

    public function getType() {
        return $this->get("type");
    }

    public function setType($type) {
        return $this->set("type", $type);
    }

    public function getDecision() {
        return $this->get("decision");
    }

    public function setDecision($decision) {
        return $this->set("decision", $decision);
    }
}
