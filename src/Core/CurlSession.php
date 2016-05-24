<?php namespace Konduto\Core;

/**
 * Models curl lib calls in a object-oriented way
 * Easier for testing.
 * @package Konduto\Core
 */
class CurlSession {

    private $handle;
    private $responseBody;

    public function __construct($url) {
        $this->handle = curl_init($url);
    }

    public function setOption($name, $value) {
        curl_setopt($this->handle, $name, $value);
    }

    public function setOptionsArray(array $optionsArray) {
        curl_setopt_array($this->handle, $optionsArray);
    }

    public function execute() {
        $this->responseBody = curl_exec($this->handle);
    }

    public function getInfo($name) {
        return curl_getinfo($this->handle, $name);
    }

    public function getErrorNo() {
        return curl_errno($this->handle);
    }

    public function close() {
        curl_close($this->handle);
    }

    public function getResponseBody() {
        return $this->responseBody;
    }
}