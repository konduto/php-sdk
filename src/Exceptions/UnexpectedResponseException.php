<?php namespace Konduto\Exceptions;

class UnexpectedResponseException extends KondutoException {

    public function __construct($message) {
        parent::__construct("Unexpected response from Konduto API: $message -
                             Please, contact bugs@konduto.com");
    }
}
