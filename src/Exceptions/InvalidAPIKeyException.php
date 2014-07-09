<?php namespace Konduto\exceptions;

/**
 * This exception is thrown when the provided key is not a base64,
 * or, when trying to perform a transaction, the API responds with "invalid key" error.
 */

class InvalidAPIKeyException extends KondutoException {

    public function __construct($key) {
        $message = "Provided API key '{$key}' is not valid";
        parent::__construct($message);
    }
}