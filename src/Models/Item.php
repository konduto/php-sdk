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
        $this->set("sku", $sku);
        return $this;
    }

    public function getProductCode() {
        return $this->get("product_code");
    }

    public function setProductCode($productCode) {
        $this->set("product_code", $productCode);
        return $this;
    }

    public function getCategory() {
        return $this->get("category");
    }

    public function setCategory($category) {
        $this->set("category", $category);
        return $this;
    }

    public function getName() {
        return $this->get("name");
    }

    public function setName($name) {
        $this->set("name", $name);
        return $this;
    }

    public function getDescription() {
        return $this->get("description");
    }

    public function setDescription($description) {
        $this->set("description", $description);
        return $this;
    }

    public function getUnitCost() {
        return $this->get("unit_cost");
    }

    public function setUnitCost($unitCost) {
        $this->set("unit_cost", $unitCost);
        return $this;
    }

    public function getQuantity() {
        return $this->get("quantity");
    }

    public function setQuantity($quantity) {
        $this->set("quantity", $quantity);
        return $this;
    }

    public function getDiscount() {
        return $this->get("discount");
    }

    public function setDiscount($discount) {
        $this->set("discount", $discount);
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt() {
        return $this->get("created_at");
    }

    public function setCreatedAt($createdAt) {
        $this->set("created_at", $createdAt);
        return $this;
    }
}
