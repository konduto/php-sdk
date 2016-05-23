<?php namespace Konduto\Core;

use \Konduto\Models\Order;
use \Konduto\Exceptions;
use \Konduto\Params;

/**
 * Konduto SDK
 *
 * This class performs Konduto API functions as described in Konduto documentation
 * at http://docs.konduto.com/.
 *
 * This class works as a singleton.
 *
 * Behind the static methods, it uses php cURL library to perform HTTP requests
 * to Konduto API endpoint. It automatically generates and parses messages exchanged
 * with the API.
 *
 * The available methods are:
 * - setApiKey
 * - sendOrder
 * - analyze
 * - updateOrderStatus
 * - getOrder
 *
 * @api v1
 * @version v2
 */
abstract class Konduto {

    public static $key = "";
    private static $useSSL = true;
    protected static $additionalCurlOpts = array();

    /**
     * setApiKey
     *
     * Check if the string provided is a valid API key and use it
     * when performing requests to Konduto API.
     *
     * @param string
     */
    public static function setApiKey($key) {
        if (self::isValidKey($key)) {
            self::$key = $key;
            self::$useSSL = $key[0] == 'P';
            return true;
        }
        throw new Exceptions\InvalidAPIKeyException("Invalid API key: $key");
    }

    /**
     * getOrder
     *
     * Query Konduto API for an order given its order id.
     *
     * @param string $orderId
     * @return \Konduto\Models\Order
     */
    public static function getOrder($orderId) {
        $response = self::requestApi("get", null, $orderId);
        $body = $response->getBodyAsJson();
        $order = new Order($body["order"]);
        return $order;
    }

    /**
     * analyze
     *
     * Send an order information for fraud analysis, the object returned
     * is an Order object with its' 'recommendation' and 'score' fields populated.
     *
     * @param \Konduto\Models\Order $order
     * @return \Konduto\Models\Order
     */
    public static function analyze(Order $order) {
        $orderJson = $order->toJsonArray();
        $response = self::requestApi("post", $orderJson);
        $responseJson = $response->getBodyAsJson();
        if (!key_exists("order", $responseJson))
            throw new Exceptions\UnexpectedResponseException("Response has no 'order': $responseJson");
        $newOrder = new Order(array_merge($orderJson, $responseJson["order"]));
        return $newOrder;
    }

    /**
     * sendOrder
     *
     * Send an order to Konduto system without prompting an analysis.
     *
     * @param \Konduto\Models\Order $order
     * @return \Konduto\Models\Order
     */
    public static function sendOrder(Order $order) {
        $order->setAnalyzeFlag(false);
        return self::analyze($order);
    }

    /**
     * updateOrderStatus
     *
     * Update the status of a previously sent order given its' id.
     *
     * @param string $orderId
     * @param string $status
     * @param string $comments
     * @return bool success
     */
    public static function updateOrderStatus($orderId, $status, $comments) {
        $body = array("status" => $status, "comments" => $comments);
        $response = self::requestApi("put", $body, $orderId);
        return $response->isOk();
    }

    /**
     * requestApi
     *
     * Perform an http request to Konduto's API endpoint.
     *
     * @param string $method
     * @param array $body parseable to json (optional)
     * @param string $id (optional)
     * @return \Konduto\Core\HttpResponse
     */
    private static function requestApi($method, $body=null, $id=null) {
        $uri = Params::ENDPOINT . '/orders';
        if ($method == "get" || $method == "put")
            $uri .= "/$id";

        $request = new HttpRequest($method, $uri, self::$useSSL, self::$additionalCurlOpts);
        $request->setBasicAuthorization(self::$key);
        if (!is_null($body)) $request->setBodyAsJson($body);

        $response = $request->send();
        $response->checkCurlResponse();
        $jsonBody = $response->getBodyAsJson();
        if (!$response->isOk() || is_null($jsonBody) || !self::isBodyStatusOk($jsonBody)) {
            $httpStatus = $response->getHttpStatus();
            throw Exceptions\KondutoException::buildFromHttpStatus($jsonBody, $httpStatus);
        }

        return $response;
    }

    /**
     * Add custom curl options
     * @param array $optionsArray
     */
    public static function setCurlOptions(array $optionsArray) {
        self::$additionalCurlOpts = $optionsArray;
    }

    /**
     * Check for 'status':'ok' in a response body
     * @param array assoc array from json response
     */
    private static function isBodyStatusOk(array $jsonBody) {
        return key_exists("status", $jsonBody) && $jsonBody["status"] == "ok";
    }

    /**
     * Check whether a string is a valid Konduto API key
     * @param string $key
     */
    private static function isValidKey($key) {
        return is_string($key) && strlen($key) == 21 && ($key[0] == 'T' or $key[0] == 'P');
    }
}
