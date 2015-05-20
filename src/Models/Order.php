<?php namespace Konduto\Models;

const STATUS_PENDING         = "pending";
const STATUS_APPROVED        = "approved";
const STATUS_DECLINED        = "declined";
const STATUS_FRAUD           = "fraud";
const STATUS_NOT_AUTHORIZED  = "not_authorized";

const RECOMMENDATION_APPROVE = "approve";
const RECOMMENDATION_DECLINE = "decline";
const RECOMMENDATION_REVIEW  = "review";

class Order extends Model {

    protected $_schema_key = "order";

    protected $_properties = array(
        "id" => null,
        "visitor" => null,
        "total_amount" => null,
        "shipping_amount" => null,
        "tax_amount" => null,
        "currency" => null,
        "installments" => null,
        "ip" => null,
        "payment" => array(),
        "customer" => null,
        "billing" => null,
        "shipping" => null,
        "shopping_cart" => array(),
        "travel" => null
    );

    protected $_mandatory_fields = array("id", "total_amount", "customer");

    protected $timestamp;
    protected $status;
    protected $device;
    protected $geolocation;
    protected $recommendation;
    protected $score;
    protected $navigation;
    protected $created_at;

    protected $available_status = array(STATUS_PENDING, STATUS_APPROVED,
             STATUS_DECLINED, STATUS_FRAUD, STATUS_NOT_AUTHORIZED);

    public function customer($value = null) {
        return $this->set_get_object("customer", $value, "Konduto\Models\Customer");
    }

    public function billing($value = null) {
        return $this->set_get_object("billing", $value, "Konduto\Models\Address");
    }

    public function shipping($value = null) {
        return $this->set_get_object("shipping", $value, "Konduto\Models\Address");
    }

    public function travel($value = null) {
        return $this->set_get_object("travel", $value, "Konduto\Models\Travel");
    }

    public function payment($payment_array = null) {
        return $this->set_get_array_object("payment",
                 $payment_array, "Konduto\Models\Payment");
    }

    public function shopping_cart($item_array = null) {
        return $this->set_get_array_object("shopping_cart",
                 $item_array, "Konduto\Models\Item");
    }

    /**
     * Adds an item that will be purchased in this order.
     * @param a Konduto\Models\Item object
     */
    public function add_item(\Konduto\Models\Item $item) {
        $this->_properties["shopping_cart"][] = $item;
    }

    /**
     * Adds a credit card used to pay for this order.
     * @param a Konduto\Models\CreditCard object
     */
    public function add_payment(\Konduto\Models\Payment $pmt) {
        $this->_properties["payment"][] = $pmt;
    }

    /**
     * The current status of the order.
     * @return one of 5 possible status:
     * 'approved', 'declined', 'pending', 'fraud' or 'not_authorized'
     */
    public function status($status = null) {
        if (!isset($status)) {
            return $this->get_status();
        }
        else if (in_array($status, $this->available_status)) {
            $this->status = $status;
        }
    }

    public function timestamp($timestamp = null) {
        if (!isset($timestamp)) {
            return $this->timestamp;
        }
        else {
            $this->timestamp = $timestamp;
        }
    }

    public function created_at($timestamp = null) {
        if (!isset($timestamp)) {
            return $this->created_at;
        }
        else {
            $this->created_at = $timestamp;
        }
    }

    /**
     * If this order was already subject to analysis by Konduto,
     * returns information
     * about the device used by the customer that submitted order.
     * @return a Konduto\Models\Device object
     */
    public function device($device = null) {
        if (!isset($device)) {
            return $this->device;
        }
        else if (is_array($device)) {
            $this->device = new Device($device);
        }
        else {
            $this->device = $device;
        }
    }

    /**
     * If this order was already subject to analysis by Konduto,
     * returns information
     * about the geolocation of the order.
     * @return a Konduto\Models\Geolocation object
     */
    public function geolocation($geo = null) {
        if (!isset($geo)) {
            return $this->geolocation;
        }
        else if (is_array($geo)) {
            $this->geolocation = new Geolocation($geo);
        }
        else {
            $this->geolocation = $geo;
        }
    }

    /**
     * If this order was already subject to analysis by Konduto,
     * returns the recommendation
     * @return one of three possible recommendations: 'approve',
     * 'decline' or 'review'
     */
    public function recommendation($reco = null) {
        if (!isset($reco)) {
            return $this->recommendation;
        }
        else {
            $this->recommendation = strtolower($reco);
        }
    }

    public function score($score = null) {
        if (!isset($score)) {
            return $this->score;
        }
        else {
            $this->score = $score;
        }
    }

    /**
     * If this order was already subject to analysis by Konduto,
     * returns navigation
     * information regarding the customer who performer this order.
     * @return a Konduto\Models\Navigation object
     */
    public function navigation($nav = null) {
        if (!isset($nav)) {
            return $this->navigation;
        }
        else if (is_array($nav)) {
            $this->navigation = new Navigation($nav);
        }
        else {
            $this->navigation = $nav;
        }
    }

    /**
     * Returns the status of an order
     *
     * @param recommendation string
     *
     * @return status string
     */
    protected function get_status() {
        if (is_string($this->status)) {
            return $this->status;
        }

        $this->status = null;

        // If a payment has 'declined' status, status of the order
        // is 'not_autorized'
        foreach ($this->payment() as $payment) {
            if ($payment->status() === PAYMENT_DECLINED) {
                $this->status = STATUS_NOT_AUTHORIZED;
                return $this->status;
            }
        }

        switch ($this->recommendation()) {
            case RECOMMENDATION_REVIEW:
                $this->status = STATUS_PENDING;
                break;
            case RECOMMENDATION_APPROVE:
                $this->status = STATUS_APPROVED;
                break;
            case RECOMMENDATION_DECLINE:
                $this->status = STATUS_DECLINED;
                break;
        }

        return $this->status;
    }
}
