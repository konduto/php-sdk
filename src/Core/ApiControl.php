<?php namespace Konduto\Core;

use \Konduto\Models as Models;
use \Konduto\Exceptions as Exceptions;

/*
 * Defines what URL to communicate with.
 */
const ENDPOINT        = "https://api.konduto.com/";

/*
 * Current version of the SDK.
 */
const CURRENT_VERSION = "v1";

/*
 * Number of seconds before giving up of waiting for a response from Konduto API
 */
const API_TIMEOUT     = 30;      // In seconds.

/*
 * Constants used for switching between HTTP methods use inside ApiControl class
 */
const METHOD_GET  = 0;
const METHOD_POST = 1;
const METHOD_PUT  = 2;

/*
 * Important messages that might be responded by Konduto API
 */
const MSG_DUPLICATE_ORDER  = "record already exists";

/*
 * List of HTTP status codes used by SDK
 */
const HTTP_OK             = 200;
const HTTP_UNAUTHORIZED   = 401;
const HTTP_FORBIDDEN      = 403;
const HTTP_NOT_FOUND      = 404;
const HTTP_INTERNAL_ERROR = 500;

/**
 * API specific control methods
 *  
 * This class describes details of the API functioning, such as use of curl library.
 * It provides auxiliary methods to be used by the public methods.
 * All its methods are protected. 
 *
 * @ignore
 */ 
abstract class ApiControl {

    protected static $version = CURRENT_VERSION;  // Version of Konduto API to be used
    protected static $key;                        // Secret key used for Konduto API
    protected static $lastResponse;               // String containing last response from a request to Konduto API

    /**
     * Method for sending an HTTP request to Konduto API at the specified ENDPOINT
     *
     * This method sends an HTTP request to an URL defined by the constant ENDPOINT
     * adding the API version and a relative path provided in the arguments.
     * The body of the request is specified by the parameter data.
     *
     * @param $data a string containing the body of the request.
     * @param $method accepts one of the numerical constants METHOD_* defined above.
     * @param $relative_url the url to be constructed using ENDPOINT and the set version.
     *
     * @throws InvalidAPIKeyException if an API key was not set (with setApiKey method) or if Konduto API could not recognize the set key as a valid API key
     * @throws CommunicationErrorException if there it could not reach the API
     * @throws TimeoutException if the SDK is waiting for a response for TIMEOUT seconds
     * @throws KondutoAPIErrorException if the API responds with a message with http status 500 (internal error)
     * @throws OperationNotAllowedException the operation being performed is not allowed for the set API key
     *
     * @return An associative array with the response of the api and the returned status code.
     */
    protected static function sendRequest($data, $method, $relative_url) {

        if (!isset(self::$key)) {
            throw new Exceptions\InvalidAPIKeyException(self::$key);
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
                throw new Exceptions\TimeoutException();
                break;
            case CURLE_COULDNT_CONNECT:
            default:
                throw new Exceptions\CommunicationErrorException($errno);
        }

        self::$lastResponse = $response;

        switch ($http_status) {
            case HTTP_INTERNAL_ERROR:           
                throw new Exceptions\KondutoAPIErrorException();
                break;
            case HTTP_UNAUTHORIZED:
                throw new Exceptions\InvalidAPIKeyException(self::$key);
                break;
            case HTTP_FORBIDDEN:
                throw new Exceptions\OperationNotAllowedException();
                break;
            default:
                // Do nothing.
        }

        $response_array = json_decode($response, true, 8);
        $response_array["http_status"] = $http_status;

        return $response_array;
    }

    /**
     * Checks whether the version provided is valid or not. If it is not valid, throws and exception.
     *
     * @todo  This function needs to be updated when new versions of the API get available.
     *
     * @param version a string containing the version to be set
     * 
     * @throws InvalidVersionException if the version cannot be recognized
     */
    protected static function validate_version($version) {
        if ($version != CURRENT_VERSION) {
            throw new Exceptions\InvalidVersionException($version);
        }
    }

    /**
     * Returns the status of an order given a recommendation
     * 
     * @param recommendation string
     *
     * @return status string
     */
    protected static function get_status($recommendation) {
        switch (strtolower($recommendation)) {
            case Models\RECOMMENDATION_REVIEW:
                return Models\STATUS_PENDING;
            case Models\RECOMMENDATION_APPROVE:
                return Models\STATUS_APPROVED;
            case Models\RECOMMENDATION_DECLINE:
                return Models\STATUS_DECLINED;
            default:
                return null;
        }
    }

    /**
     * Check if the response was successful, throw Exceptions in case of errors.
     * 
     * @throws DuplicateOrderException there is a message indicating the order being persisted is duplicate
     * @throws KonduoAPIErrorException when the API responds nor error neither OK
     *
     * @return true if success
     */
    protected static function check_post_response($response, $order_id = null) {
        if ($response["http_status"] == HTTP_OK) {
            return true;
        }
        else if (isset($response["status"]) and $response["status"] == "error" and isset($response["message"])
            and isset($response["message"]["why"]) and isset($response["message"]["why"]["found"])
            and $response["message"]["why"]["found"] == MSG_DUPLICATE_ORDER) {
            throw new Exceptions\DuplicateOrderException($order_id);
        }
        else {
            throw new Exceptions\KondutoAPIErrorException();
        }
    }

    /**
     * Check an HTTP response to order found.
     * 
     * @param response HTTP
     * @param order id, to properly build exception if error happens
     *
     * @throws OrderNotFoundException if http status is 404
     * 
     * @return true if success
     */
    protected static function was_order_found($response, $order_id = null) {
        if ($response["http_status"] == HTTP_OK) {
            return true;
        }
        else if ($response["http_status"] == HTTP_NOT_FOUND) {
            throw new Exceptions\OrderNotFoundException($order_id);
        }
        else {
            return false;
        }
    }

    /**
     * Check whether the provided key is a production key.
     *
     * @param key
     * 
     * @return true if provided keys is a production key
     */
    protected static function is_production_key($key) {
        return is_string($key) && $key[0] == 'P';
    }

    /**
     * Returns the last response from Konduto API.
     *
     * @return last response message from Konduto API.
     */
    public static function getLastResponse() {
        return self::$lastResponse;
    }
}