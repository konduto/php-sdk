<?php namespace Konduto\Models;

class BureauxQuery extends BaseModel {

    /**
     * @inheritdoc
     */
    protected function fields() {
        return array("service", "response");
    }

    public function getService() {
        return $this->get("service");
    }

    public function setService($service) {
        return $this->set("service", $service);
    }

    public function getResponse() {
        return $this->get("response");
    }

    public function setResponse($response) {
        return $this->set("response", $response);
    }
}
