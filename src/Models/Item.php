<?php namespace Konduto\Models;

use Konduto\Parsers\DateParser;

class Item extends BaseModel {

    /**
     * @inheritdoc
     */
    protected function fields() {
        return array("sku", "product_code", "category", "description",
            "name", "unit_cost", "quantity", "discount", "created_at");
    }

    /**
     * @inheritDoc
     */
    protected function initParsers() {
        return array("created_at" => new DateParser());
    }


    public function getSku() {
        return $this->get("sku");
    }

    public function setSku($sku) {
        return $this->set("sku", $sku);
    }

    public function getProductCode() {
        return $this->get("product_code");
    }

    public function setProductCode($productCode) {
        return $this->set("product_code", $productCode);
    }

    public function getCategory() {
        return $this->get("category");
    }

    public function setCategory($category) {
        return $this->set("category", $category);
    }

    public function getName() {
        return $this->get("name");
    }

    public function setName($name) {
        return $this->set("name", $name);
    }

    public function getDescription() {
        return $this->get("description");
    }

    public function setDescription($description) {
        return $this->set("description", $description);
    }

    public function getUnitCost() {
        return $this->get("unit_cost");
    }

    public function setUnitCost($unitCost) {
        return $this->set("unit_cost", $unitCost);
    }

    public function getQuantity() {
        return $this->get("quantity");
    }

    public function setQuantity($quantity) {
        return $this->set("quantity", $quantity);
    }

    public function getDiscount() {
        return $this->get("discount");
    }

    public function setDiscount($discount) {
        return $this->set("discount", $discount);
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt() {
        return $this->get("created_at");
    }

    public function setCreatedAt($createdAt) {
        return $this->set("created_at", $createdAt);
    }
}
