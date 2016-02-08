<?php namespace Konduto\Exceptions;

use Konduto\Core\HttpResponse;

class KondutoException extends \Exception {

    const RECORD_EXISTS = "record already exists";
    static protected $httpCode = 0;

    public function __construct($body) {
        if (is_array($body)) {
            $options = defined('JSON_PRETTY_PRINT') ?
                constant('JSON_PRETTY_PRINT') : 0;
            $body = json_encode($body, $options);
        }
        parent::__construct($body, self::$httpCode);
    }

    public function __toString() {
        $code = $this->code ? " - $this->code -" : "";
        return "Konduto exception: " . get_class($this) . "$code [{$this->message}].";
    }

    static public function buildFromHttpStatus($body, $code) {
        if (self::isDuplicateOrder($code, $body))  // Only way of discovering duplicate order
            return new DuplicateOrderException($body);
        foreach (get_declared_classes() as $ExceptionClass) {
            if ($ExceptionClass instanceof KondutoException
                    && $ExceptionClass::$httpCode == $code) {
                return new $ExceptionClass($body);
            }
        }
        return new KondutoException($body);
    }

    static private function isDuplicateOrder($code, $body) {
        if ($code == HttpResponse::HTTP_STATUS_BAD_REQUEST
                && is_array($body) && key_exists("message", $body)) {
            return $body["message"]["why"]["found"] == self::RECORD_EXISTS;
        }
        return false;
    }
}
