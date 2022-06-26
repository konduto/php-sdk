<?php namespace Konduto\Models;

class Delivery extends BaseModel {

    /**
     * @inheritdoc
     */
    protected function fields() {
        return array("estimatedDeliveryDate", "estimatedShippingDate", "deliveryCompany", "deliveryMethod");
    }

    public function getEstimatedDeliveryDate() {
        return $this->get("estimatedDeliveryDate");
    }

    public function setEstimatedDeliveryDate($value) {
        return $this->set("estimatedDeliveryDate", $value);
    }

    public function getEstimatedShippingDate() {
        return $this->get("estimatedShippingDate");
    }

    public function setEstimatedShippingDate($value) {
        return $this->set("estimatedShippingDate", $value);
    }

    public function getDeliveryCompany() {
        return $this->get("deliveryCompany");
    }

    public function setDeliveryCompany($value) {
        return $this->set("deliveryCompany", $value);
    }

    public function getDeliveryMethod() {
        return $this->get("deliveryMethod");
    }

    public function setDeliveryMethod($value) {
        return $this->set("deliveryMethod", $value);
    }

}
