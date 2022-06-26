<?php namespace Konduto\Models;

use Konduto\Parsers\ArrayModelParser;
use Konduto\Parsers\BankArrayParser;
use Konduto\Parsers\DateTimeParser;
use Konduto\Parsers\ModelParser;
use Konduto\Parsers\PaymentArrayParser;
use Konduto\Parsers\HotelRoomArrayParser;
use Konduto\Parsers\TravelParser;
use Konduto\Parsers\HotelParser;

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
            "currency", "installments", "ip", "payment", "customer", "billing", "hotel",
            "shipping", "shopping_cart", "travel", "purchased_at", "first_message",
            "messages_exchanged", "seller", "analyze", "bureaux_queries", "events", "agentSeller", "pointOfSale", "delivery");
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
            "travel" => new TravelParser(),
            "hotel"  => new ModelParser('Konduto\Models\Hotel'),
            "bureaux_queries" => new ArrayModelParser('Konduto\Models\BureauxQuery'),
            "events" => new ArrayModelParser('Konduto\Models\Event'),
            "triggered_rules" => new ArrayModelParser('Konduto\Models\TriggeredRule'),
            "triggered_decision_list" => new ArrayModelParser('Konduto\Models\TriggeredDecisionList'),
            "agentSeller" => new ModelParser('Konduto\Models\AgentSeller'),
            "pointOfSale" => new ModelParser('Konduto\Models\PointOfSale'),
            "delivery" => new ModelParser('Konduto\Models\Delivery'),
            "BankOriginAccount" => new BankArrayParser(),
            "BankDestinationAccount" => new BankArrayParser()
        );
    }

    public function getId() {
        return $this->get("id");
    }

    public function setId($value) {
        return $this->set("id", $value);
    }

    public function getVisitor() {
        return $this->get("visitor");
    }

    public function setVisitor($value) {
        return $this->set("visitor", $value);
    }

    public function getTotalAmount() {
        return $this->get("total_amount");
    }

    public function setTotalAmount($value) {
        return $this->set("total_amount", $value);
    }

    public function getShippingAmount() {
        return $this->get("shipping_amount");
    }

    public function setShippingAmount($value) {
        return $this->set("shipping_amount", $value);
    }

    public function getTaxAmount() {
        return $this->get("tax_amount");
    }

    public function setTaxAmount($value) {
        return $this->set("tax_amount", $value);
    }

    public function getCurrency() {
        return $this->get("currency");
    }

    public function setCurrency($value) {
        return $this->set("currency", $value);
    }

    public function getInstallments() {
        return $this->get("installments");
    }

    public function setInstallments($value) {
        return $this->set("installments", $value);
    }

    public function getIp() {
        return $this->get("ip");
    }

    public function setIp($value) {
        return $this->set("ip", $value);
    }

    /**
     * @return \Konduto\Models\Payment[]
     */
    public function getPayment() {
        return $this->get("payment");
    }

    public function setPayment(array $value) {
        return $this->set("payment", $value);
    }

    /**
     * @return \Konduto\Models\Hotel[]
     */
    public function getHotel() {
        return $this->get("hotel");
    }

    public function setHotel(array $value) {
        return $this->set("hotel", $value);
    }

    /**
     * @return \Konduto\Models\Event[]
     */
    public function getEvents() {
        return $this->get("events");
    }

    public function setEvents(array $value) {
        return $this->set("events", $value);
    }

    /**
     * @return \Konduto\Models\Customer
     */
    public function getCustomer() {
        return $this->get("customer");
    }

    public function setCustomer($value) {
        return $this->set("customer", $value);
    }

    /**
     * @return \Konduto\Models\Address
     */
    public function getBilling() {
        return $this->get("billing");
    }

    public function setBilling($value) {
        return $this->set("billing", $value);
    }

    /**
     * @return \Konduto\Models\Address
     */
    public function getShipping() {
        return $this->get("shipping");
    }

    public function setShipping($value) {
        return $this->set("shipping", $value);
    }

    /**
     * @return \Konduto\Models\Item[]
     */
    public function getShoppingCart() {
        return $this->get("shopping_cart");
    }

    public function setShoppingCart(array $value) {
        return $this->set("shopping_cart", $value);
    }

    /**
     * @return \Konduto\Models\Travel
     */
    public function getTravel() {
        return $this->get("travel");
    }

    public function setTravel($value) {
        return $this->set("travel", $value);
    }

    /**
     * @return \DateTime
     */
    public function getPurchasedAt() {
        return $this->get("purchased_at");
    }

    public function setPurchasedAt($value) {
        return $this->set("purchased_at", $value);
    }

    /**
     * @return \DateTime
     */
    public function getFirstMessage() {
        return $this->get("first_message");
    }

    public function setFirstMessage($value) {
        return $this->set("first_message", $value);
    }

    public function getMessagesExchanged() {
        return $this->get("messages_exchanged");
    }

    public function setMessagesExchanged($value) {
        return $this->set("messages_exchanged", $value);
    }

    /**
     * @return \Konduto\Models\Seller
     */
    public function getSeller() {
        return $this->get("seller");
    }

    public function setSeller($value) {
        return $this->set("seller", $value);
    }

    public function getTimestamp() {
        return $this->get("timestamp");
    }

    public function setTimestamp($value) {
        return $this->set("timestamp", $value);
    }

    public function getStatus() {
        return $this->get("status");
    }

    public function setStatus($value) {
        return $this->set("status", $value);
    }

    /**
     * @return \Konduto\Models\Device
     */
    public function getDevice() {
        return $this->get("device");
    }

    public function setDevice($value) {
        return $this->set("device", $value);
    }

    /**
     * @return \Konduto\Models\Geolocation
     */
    public function getGeolocation() {
        return $this->get("geolocation");
    }

    public function setGeolocation($value) {
        return $this->set("geolocation", $value);
    }

    public function getRecommendation() {
        return $this->get("recommendation");
    }

    public function setRecommendation($value) {
        return $this->set("recommendation", $value);
    }

    public function getScore() {
        return $this->get("score");
    }

    public function setScore($value) {
        return $this->set("score", $value);
    }

    /**
     * @return \Konduto\Models\Navigation
     */
    public function getNavigation() {
        return $this->get("navigation");
    }

    public function setNavigation($value) {
        return $this->set("navigation", $value);
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt() {
        return $this->get("created_at");
    }

    public function setCreatedAt($value) {
        return $this->set("created_at", $value);
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt() {
        return $this->get("updated_at");
    }

    public function setUpdatedAt($value) {
        return $this->set("updated_at", $value);
    }

    public function getAnalyzeFlag() {
        return $this->get("analyze");
    }

    public function setAnalyzeFlag($value) {
        return $this->set("analyze", $value);
    }

    public function getBureauxQueries() {
        return $this->get("bureaux_queries");
    }

    public function getTriggeredRules() {
        return $this->get("triggered_rules");
    }

    public function getTriggeredDecisionList() {
        return $this->get("triggered_decision_list");
    }

    /**
     * @return \Konduto\Models\AgentSeller
     */
    public function getAgentSeller() {
        return $this->get("agentSeller");
    }

    public function setAgentSeller($value) {
        return $this->set("agentSeller", $value);
    }
    
    /**
     * @return \Konduto\Models\PointOfSale
     */
    public function getPointOfSale() {
        return $this->get("pointOfSale");
    }

    public function setPointOfSale($value) {
        return $this->set("pointOfSale", $value);
    }

    /**
     * @return \Konduto\Models\Delivery
     */
    public function getDelivery() {
        return $this->get("delivery");
    }

    public function setDelivery($value) {
        return $this->set("delivery", $value);
    }

   /**
     * @return \Konduto\Models\BankOriginAccount
     */
    public function getBankOriginAccount() {
        return $this->get("bankOriginAccount");
    }

    public function setBankOriginAccount($value) {
        return $this->set("bankOriginAccount", $value);
    }

    /**
     * @return \Konduto\Models\BankDestinationAccount[]
     */
    public function getBankDestinationAccount() {
        return $this->get("bankDestinationAccount");
    }

    public function setBankDestinationAccount(array $value) {
        return $this->set("bankDestinationAccount", $value);
    }

}
