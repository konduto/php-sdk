<?php
namespace konduto\exceptions;
require_once "KondutoException.php";

class InvalidVersionException extends KondutoException {

    public function __construct($version) {
        $message = "Provided version '{$version}' is not valid";
        parent::__construct($message);
    }
}