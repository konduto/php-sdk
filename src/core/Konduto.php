<?php
namespace konduto\core;
require_once "api_control.php";
use \konduto\models as models;
use \konduto\exceptions as exceptions;

/*
 * The abstract class api is what the user uses as 'Konduto'. All its methods and variables are static.
 * There are only 6 public methods:
 * - setApiKey
 * - setVersion
 * - sendOrder
 * - analyze
 * - updateOrderStatus
 * - getOrder
 */
abstract class Konduto extends api_control {

    /**
     * Choose a version of Konduto API to be used for performing transactions.
     */
    public function setVersion($ver = CURRENT_VERSION) {
        self::validate_version($ver);
        self::$version = $ver;
    }

    /**
     * Receives a string and validates it as a base64. If it is not a base64 string, raises an exception.
     */
    public function setApiKey($key) {
        if (is_string($key) and strlen($key) == 21 and ($key[0] == 'T' or $key[0] == 'P')) {
            self::$key = $key;
            return true;
        }
        throw new exceptions\InvalidAPIKeyException($key);
    }

    /**
     * Retrieves an order given its id.
     */
    public function getOrder($id) {
        if (!models\ValidationSchema::validateOrderField('id', $id)) {
            throw new exceptions\InvalidOrderException("id");
        }

        $order_array = self::sendRequest(null, METHOD_GET, "/orders/{$id}");
        
        // Do a check in the response for an error 404.
        self::was_order_found($order_array, $id);

        $order = new models\Order();

        if (isset($order_array['customer'])) {
            $order->customer(new models\Customer($order_array['customer']));
            unset($order_array['customer']);
        }
        if (isset($order_array['billing'])) {
            $order->billing_address(new models\Address($order_array['billing']));
            unset($order_array['billing']);
        }
        if (isset($order_array['shipping'])) {
            $order->shipping_address(new models\Address($order_array['shipping']));
            unset($order_array['shipping']);
        }
        if (isset($order_array['payment'])) {
            $order->payment_array(array_map(function ($e) {return new models\CreditCard($e); }, $order_array['payment']));
            unset($order_array['payment']);
        }
        if (isset($order_array['shopping_cart'])) {
            $order->shopping_cart(array_map(function ($e) {return new models\Item($e); }, $order_array['shopping_cart']));
            unset($order_array['shopping_cart']);
        }

        $order->set($order_array);

        return $order;
    }

    /**
     * Sends an order for analyzis using Konduto API.
     */
    public function analyze(models\Order &$order, $analyze = true) {

        if (!$order->is_valid()) {
            throw new exceptions\InvalidOrderException($order->get_errors());
            return;
        }

        $order_array = $order->as_array();

        if ($analyze === false) {
            $order_array["analyze"] = false;
        }

        $response = self::sendRequest(json_encode($order_array), METHOD_POST, '/orders');

        if (self::check_post_response($response, $order->id()) and $analyze === true) {
            $order->set([
                "recommendation" => $response["order"]["recommendation"],
                "geolocation"    => $response["order"]["geolocation"],
                "device"         => $response["order"]["device"],
                "score"          => $response["order"]["score"],
                "status"         => self::get_status($response["order"]["recommendation"])
            ]);
        }

        return true;
    }

    /**
     * Persists an order without analyzing it. 
     */ 
    public function sendOrder(models\Order &$order) {
        return self::analyze($order, false);
    }

    /**
     * Updates the status of an existing order.
     */
    public function updateOrderStatus($order_id, $status, $comments = "") {

        if (!in_array($status, [models\STATUS_APPROVED, models\STATUS_DECLINED, models\STATUS_FRAUD])) {
            throw new exceptions\InvalidOrderException("status");
        }

        $json_msg = [
            "order_id" => $order_id,
            "status" => $status,
            "comments" => "$comments",
        ];

        $json_msg = json_encode($json_msg);

        $response = self::sendRequest($json_msg, METHOD_PUT, "/orders/$order_id");

        return self::was_order_found($response, $order_id);
    }
}