<?php namespace Konduto\Exceptions;

class TimeoutException extends KondutoException {

    public function __construct() {
        parent::__construct("Communication timeout for Konduto API");
    }
}