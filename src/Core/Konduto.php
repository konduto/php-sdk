<?php namespace Konduto\Core;

use \Konduto\Models\Order;
use \Konduto\Exceptions;
use \Konduto\Params;

/**
 * Konduto SDK core component
 *
 * This class performs Konduto API functions as described in Konduto documentation
 * at http://docs.konduto.com/.
 * Behind the static methods, it uses php cURL library to perform HTTP requests
 * to Konduto API endpoint. It automatically generates and parses messages exchanged
 * with the API, leaving only to the client code SDK models objects.
 *
 * The available methods are:
 * - setApiKey
 * - sendOrder
 * - analyze
 * - updateOrderStatus
 * - getOrder
 *
 * @api v1
 *
 * @version v2
 */
abstract class Konduto {

    public static $key = "";
    private static $useSSL = true;
    protected static $additionCurlOpts = array();

    public static function setApiKey($key) {
        if (is_string($key) and strlen($key) == 21 and ($key[0] == 'T' or $key[0] == 'P')) {
            self::$key = $key;
            self::$useSSL = $key[0] == 'P';
            return true;
        }
        throw new Exceptions\InvalidAPIKeyException("Invalid API key: $key");
    }

    public static function getOrder($orderId) {
        $response = self::requestApi("get", null, $orderId);
        $order = new Order($response->getBodyAsJson());
        return $order;
    }

    public static function analyze(Order $order) {
        $orderJson = $order->toJsonArray();
        $response = self::requestApi("post", $orderJson);
        $responseJson = $response->getBodyAsJson();
        $newOrder = new Order(array_merge($orderJson, $responseJson));
        return $newOrder;
    }

    public static function sendOrder(Order $order) {
        $order->setAnalyzeFlag(false);
        return self::analyze($order);
    }

    public static function updateOrderStatus($orderId, $status, $comments) {
        $body = array("status" => $status, "comments" => $comments);
        $response = self::requestApi("put", $body, $orderId);
        return $response->isOk();
    }

    protected static function requestApi($method, $body=null, $id=null) {
        $uri = Params::ENDPOINT;
        if ($method == "get" || $method == "put") $uri .= "/$id";
        $request = new HttpRequest($method, $uri, self::$useSSL, self::$additionCurlOpts);
        $request->setBasicAuthorization(self::$key);
        if ($body != null) $request->setBodyAsJson($body);

        $response = $request->send();
        $response->checkCurlResponse();
        if (!$response->isOk()) {
            $respBody = $response->getBody();
            $httpStatus = $response->getHttpStatus();
            throw Exceptions\KondutoException::buildFromHttpStatus($respBody, $httpStatus);
        }

        return $response;
    }

    protected static function setCurlOptions(array $optionsArray) {
        self::$additionCurlOpts = $optionsArray;
    }
}
