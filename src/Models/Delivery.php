<?php namespace Konduto\Models;

class Delivery extends BaseModel {

    /**
     * @inheritdoc
     */
    protected function fields() {
        return array("estimated_delivery_date", "estimated_shipping_date", "delivery_company", "delivery_method");
    }

    public function getEstimatedDeliveryDate() {
        return $this->get("estimated_delivery_date");
    }

    public function setEstimatedDeliveryDate($value) {
        return $this->set("estimated_delivery_date", $value);
    }

    public function getEstimatedShippingDate() {
        return $this->get("estimated_shipping_date");
    }

    public function setEstimatedShippingDate($value) {
        return $this->set("estimated_shipping_date", $value);
    }

    public function getDeliveryCompany() {
        return $this->get("delivery_company");
    }

    public function setDeliveryCompany($value) {
        return $this->set("delivery_company", $value);
    }

    public function getDeliveryMethod() {
        return $this->get("delivery_method");
    }

    public function setDeliveryMethod($value) {
        return $this->set("delivery_method", $value);
    }

}