<?php namespace Konduto\Models;

use Konduto\Parsers\ArrayModelParser;
use Konduto\Parsers\DateTimeParser;
use Konduto\Parsers\ModelParser;
use Konduto\Parsers\PaymentArrayParser;
use Konduto\Parsers\TravelParser;

class Order extends BaseModel {

    const STATUS_PENDING = "pending";
    const STATUS_APPROVED = "approved";
    const STATUS_DECLINED = "declined";
    const STATUS_FRAUD = "fraud";
    const STATUS_NOT_AUTHORIZED = "not_authorized";
    const STATUS_CANCELED = "canceled";

    const RECOMMENDATION_APPROVE = "approve";
    const RECOMMENDATION_DECLINE = "decline";
    const RECOMMENDATION_REVIEW = "review";
    const RECOMMENDATION_NONE = "none";

    /**
     * @inheritdoc
     */
    protected function fields() {
        return array("id", "visitor", "total_amount", "shipping_amount", "tax_amount",
            "currency", "installments", "ip", "payment", "customer", "billing",
            "shipping", "shopping_cart", "travel", "purchased_at", "first_message",
            "messages_exchanged", "seller", "analyze");
    }

    /**
     * @inheritdoc
     */
    protected function initParsers() {
        return array(
            "customer" => new ModelParser('Konduto\Models\Customer'),
            "billing" => new ModelParser('Konduto\Models\Address'),
            "shipping" => new ModelParser('Konduto\Models\Address'),
            "payment" => new PaymentArrayParser(),
            "shopping_cart" => new ArrayModelParser('Konduto\Models\Item'),
            "purchased_at" => new DateTimeParser('Y-m-d\TH:i:s\Z'),
            "first_message" => new DateTimeParser('Y-m-d\TH:i:s\Z'),
            "navigation" => new ModelParser('Konduto\Models\Navigation'),
            "geolocation" => new ModelParser('Konduto\Models\Geolocation'),
            "device" => new ModelParser('Konduto\Models\Device'),
            "created_at" => new DateTimeParser('Y-m-d\TH:i:s\Z'),
            "updated_at" => new DateTimeParser('Y-m-d\TH:i:s\Z'),
            "seller" => new ModelParser('Konduto\Models\Seller'),
            "travel" => new TravelParser()
        );
    }

    public function getId() {
        return $this->get("id");
    }

    public function setId($value) {
        $this->set("id", $value);
        return $this;
    }

    public function getVisitor() {
        return $this->get("visitor");
    }

    public function setVisitor($value) {
        $this->set("visitor", $value);
        return $this;
    }

    public function getTotalAmount() {
        return $this->get("total_amount");
    }

    public function setTotalAmount($value) {
        $this->set("total_amount", $value);
        return $this;
    }

    public function getShippingAmount() {
        return $this->get("shipping_amount");
    }

    public function setShippingAmount($value) {
        $this->set("shipping_amount", $value);
        return $this;
    }

    public function getTaxAmount() {
        return $this->get("tax_amount");
    }

    public function setTaxAmount($value) {
        $this->set("tax_amount", $value);
        return $this;
    }

    public function getCurrency() {
        return $this->get("currency");
    }

    public function setCurrency($value) {
        $this->set("currency", $value);
        return $this;
    }

    public function getInstallments() {
        return $this->get("installments");
    }

    public function setInstallments($value) {
        $this->set("installments", $value);
        return $this;
    }

    public function getIp() {
        return $this->get("ip");
    }

    public function setIp($value) {
        $this->set("ip", $value);
        return $this;
    }

    /**
     * @return \Konduto\Models\Payment[]
     */
    public function getPayment() {
        return $this->get("payment");
    }

    public function setPayment(array $value) {
        $this->set("payment", $value);
        return $this;
    }

    /**
     * @return \Konduto\Models\Customer
     */
    public function getCustomer() {
        return $this->get("customer");
    }

    public function setCustomer($value) {
        $this->set("customer", $value);
        return $this;
    }

    /**
     * @return \Konduto\Models\Address
     */
    public function getBilling() {
        return $this->get("billing");
    }

    public function setBilling($value) {
        $this->set("billing", $value);
        return $this;
    }

    /**
     * @return \Konduto\Models\Address
     */
    public function getShipping() {
        return $this->get("shipping");
    }

    public function setShipping($value) {
        $this->set("shipping", $value);
        return $this;
    }

    /**
     * @return \Konduto\Models\Item[]
     */
    public function getShoppingCart() {
        return $this->get("shopping_cart");
    }

    public function setShoppingCart(array $value) {
        $this->set("shopping_cart", $value);
        return $this;
    }

    /**
     * @return \Konduto\Models\Travel
     */
    public function getTravel() {
        return $this->get("travel");
    }

    public function setTravel($value) {
        $this->set("travel", $value);
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getPurchasedAt() {
        return $this->get("purchased_at");
    }

    public function setPurchasedAt($value) {
        $this->set("purchased_at", $value);
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getFirstMessage() {
        return $this->get("first_message");
    }

    public function setFirstMessage($value) {
        $this->set("first_message", $value);
        return $this;
    }

    public function getMessagesExchanged() {
        return $this->get("messages_exchanged");
    }

    public function setMessagesExchanged($value) {
        $this->set("messages_exchanged", $value);
        return $this;
    }

    /**
     * @return \Konduto\Models\Seller
     */
    public function getSeller() {
        return $this->get("seller");
    }

    public function setSeller($value) {
        $this->set("seller", $value);
        return $this;
    }

    public function getTimestamp() {
        return $this->get("timestamp");
    }

    public function setTimestamp($value) {
        $this->set("timestamp", $value);
        return $this;
    }

    public function getStatus() {
        return $this->get("status");
    }

    public function setStatus($value) {
        $this->set("status", $value);
        return $this;
    }

    /**
     * @return \Konduto\Models\Device
     */
    public function getDevice() {
        return $this->get("device");
    }

    public function setDevice($value) {
        $this->set("device", $value);
        return $this;
    }

    /**
     * @return \Konduto\Models\Geolocation
     */
    public function getGeolocation() {
        return $this->get("geolocation");
    }

    public function setGeolocation($value) {
        $this->set("geolocation", $value);
        return $this;
    }

    public function getRecommendation() {
        return $this->get("recommendation");
    }

    public function setRecommendation($value) {
        $this->set("recommendation", $value);
        return $this;
    }

    public function getScore() {
        return $this->get("score");
    }

    public function setScore($value) {
        $this->set("score", $value);
        return $this;
    }

    /**
     * @return \Konduto\Models\Navigation
     */
    public function getNavigation() {
        return $this->get("navigation");
    }

    public function setNavigation($value) {
        $this->set("navigation", $value);
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt() {
        return $this->get("created_at");
    }

    public function setCreatedAt($value) {
        $this->set("created_at", $value);
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt() {
        return $this->get("updated_at");
    }

    public function setUpdatedAt($value) {
        $this->set("updated_at", $value);
        return $this;
    }

    public function getAnalyzeFlag() {
        return $this->get("analyze");
    }

    public function setAnalyzeFlag($value) {
        $this->set("analyze", $value);
        return $this;
    }
}
