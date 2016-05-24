<?php namespace Konduto\Exceptions;

use Konduto\Core\HttpResponse;

class KondutoException extends \Exception {

    const RECORD_EXISTS = "record already exists";
    protected $body = null;
    static protected $httpCode = 0;
    static protected $CodedClass = array();

    public function __construct($body) {
        if (is_array($body)) {
            $options = defined('JSON_PRETTY_PRINT') ? constant('JSON_PRETTY_PRINT') : 0;
            $body = json_encode($body, $options);
        }
        $this->body = $body;
        parent::__construct($body, self::$httpCode);
    }

    public function __toString() {
        $code = $this->code ? " - $this->code -" : "";
        return "Konduto exception: " . get_class($this) . "$code [{$this->message}].";
    }

    public function getBody() {
        return $this->body;
    }

    static public function buildFromHttpStatus($body, $code) {
        if (self::isDuplicateOrder($code, $body))  // Only way of discovering a duplicate order
            return new DuplicateOrderException($body);
        else return HttpCodedException::getException($code, $body);
    }

    static private function isDuplicateOrder($code, $body) {
        if ($code == HttpResponse::HTTP_STATUS_BAD_REQUEST
                && array_has_path(array("message", "why", "found"), $body)) {
            return $body["message"]["why"]["found"] == self::RECORD_EXISTS;
        }
        return false;
    }
}

/*
 * Utils
 */
function array_has_path($key_path = array(), $array) {
    $currArray = $array;
    foreach ($key_path as $key) {
        if (is_array($currArray) && key_exists($key, $currArray))
            $currArray = $currArray[$key];
        else return false;
    }
    return true;
}
