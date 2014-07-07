<?php
namespace konduto\core;
require_once "src/models/Order.php";
require_once "src/models/validation.php";
require_once "src/exceptions/KondutoException.php";
require_once "src/exceptions/InvalidVersionException.php";
require_once "src/exceptions/InvalidAPIKeyException.php";
require_once "src/exceptions/InvalidOrderException.php";
require_once "src/exceptions/TimeoutException.php";
require_once "src/exceptions/CommunicationErrorException.php";
require_once "src/exceptions/DuplicateOrderException.php";
require_once "src/exceptions/OrderNotFoundException.php";
require_once "src/exceptions/KondutoAPIErrorException.php";
use \konduto\models as models;
use \konduto\exceptions as exceptions;

// Constants used to define API specific configuration (they should change only with versions of the sdk).
// const ENDPOINT        = "https://api.konduto.com/";
const ENDPOINT        = "http://127.0.0.1:8080/";
const CURRENT_VERSION = "v1";
const API_TIMEOUT     = 30;      // In seconds.

// Constants used to switch between HTTP methods
const METHOD_GET  = 0;
const METHOD_POST = 1;
const METHOD_PUT  = 2;

// Messages responded by Konduto API
const MSG_DUPLICATE_ORDER  = "record already exists";

// HTTP status codes
const HTTP_OK             = 200;
const HTTP_UNAUTHORIZED   = 401;
const HTTP_NOT_FOUND      = 404;
const HTTP_INTERNAL_ERROR = 500;

/**
 * This class describes details of the API functioning, such as use of curl library.
 * It provides auxiliary methods to be used by the public methods.
 * All its methods are protected.
 */ 
abstract class api_control {

    protected static $version = CURRENT_VERSION;  // Version of Konduto API to be used
    protected static $key;                        // Secret key used for Konduto API
    protected static $lastResponse;               // String containing last response from a request to Konduto API

    /**
     * This method sends a request of a chosen method, allowing for passing data
     * as a string, to a relative URL for the ENDPOINT defined.
     * @param $data a string containing the body of the request.
     * @param $method accepts one of the numerical constants METHOD_* defined above.
     * @param $relative_url the url to be constructed using ENDPOINT and the set version.
     */
    protected function sendRequest($data, $method, $relative_url) {

        if (!isset(self::$key)) {
            throw new exceptions\InvalidAPIKeyException(self::$key);
        }

        // Builds URL
        $url = ENDPOINT . self::$version . $relative_url;

        $curlSession = curl_init($url);

        $headers = [
            "Authorization: Basic " . base64_encode(self::$key)
        ];

        // Add additional headers
        if ($data_length = strlen($data)) {
            $headers[] = "Content-Length: {$data_length}";
            $headers[] = "Content-Type: application/json";
        }

        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => API_TIMEOUT,
            CURLOPT_FAILONERROR    => false,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_SSL_VERIFYPEER => self::is_production_key(self::$key)
        ];

        // Add method-specific options
        switch ($method) {
            case METHOD_POST:
                $options[CURLOPT_POST] = true;
                $options[CURLOPT_POSTFIELDS] = $data;
                break;
            case METHOD_PUT:
                $options[CURLOPT_CUSTOMREQUEST] = 'PUT';
                $options[CURLOPT_POSTFIELDS] = $data;
                break;
            case METHOD_GET:
            default:
                // GET is curl's default method. Do nothing.
        }

        curl_setopt_array($curlSession, $options);

        // Perform request and get response
        $response = curl_exec($curlSession);

        // Get HTTP status code
        $http_status = curl_getinfo($curlSession, CURLINFO_HTTP_CODE);

        // Retrieve error number
        $errno = curl_errno($curlSession);

        // Close connection
        curl_close($curlSession);

        // Treat error
        switch ($errno) {
            case CURLE_OK:
                // No errors.
                break;
            case CURLE_OPERATION_TIMEDOUT:
                throw new exceptions\TimeoutException();
                break;
            case CURLE_COULDNT_CONNECT:
            default:
                throw new exceptions\CommunicationErrorException($errno);
        }

        self::$lastResponse = $response;

        if ($http_status == HTTP_INTERNAL_ERROR) {
            throw new exceptions\KondutoAPIErrorException();
        }
        else if ($http_status == HTTP_UNAUTHORIZED) {
            throw new exceptions\InvalidAPIKeyException(self::$key);            
        }

        $response_array = json_decode($response, true, 8);
        $response_array["http_status"] = $http_status;

        return $response_array;
    }

    /**
     * Checks whether the version provided is valid or not. If it is not valid, throws and exception.
     * TODO: This function needs to be updated when new versions of the API get available.
     */
    protected function validate_version($version) { 
        if ($version != CURRENT_VERSION) {
            throw new exceptions\InvalidVersionException($version);
        }
        return true;
    }

    /**
     * Returns the status of an order given a recommendation.
     */
    protected function get_status($recommendation) {
        switch (strtolower($recommendation)) {
            case models\RECOMMENDATION_REVIEW:
                return models\STATUS_PENDING;
            case models\RECOMMENDATION_APPROVE:
                return models\STATUS_APPROVED;
            case models\RECOMMENDATION_DECLINE:
                return models\STATUS_DECLINED;
            default:
                return null;
        }
    }

    /**
     * Check if the response was successful, throw exceptions in case of errors.
     */
    protected function check_post_response($response, $order_id = null) {
        if ($response["http_status"] == HTTP_OK) {
            return true;
        }
        else if (isset($response["status"]) and $response["status"] == "error" and isset($response["message"])
            and isset($response["message"]["why"]) and isset($response["message"]["why"]["found"])
            and $response["message"]["why"]["found"] == MSG_DUPLICATE_ORDER) {
            throw new exceptions\DuplicateOrderException($order_id);
        }
        else {
            throw new exceptions\KondutoAPIErrorException();
        }
    }

    /**
     * Check if the order exists.
     */
    protected function was_order_found($response, $order_id = null) {
        if ($response["http_status"] == HTTP_OK) {
            return true;
        }
        else if ($response["http_status"] == HTTP_NOT_FOUND) {
            throw new exceptions\OrderNotFoundException($order_id);
        }
        else {
            return false;
        }
    }

    /**
     * Check whether the provided key is a production key.
     */
    protected function is_production_key($key) {
        return is_string($key) && $key[0] == 'P';
    }

    /**
     * Returns the last response from Konduto API.
     */
    public function getLastResponse() {
        return self::$lastResponse;
    }
}