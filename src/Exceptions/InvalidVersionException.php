<?php namespace Konduto\Exceptions;

class InvalidVersionException extends KondutoException {

    public function __construct($version) {
        $message = "Provided version '{$version}' is not valid";
        parent::__construct($message);
    }
}