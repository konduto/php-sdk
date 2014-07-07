<?php
namespace konduto\exceptions;
require_once "KondutoException.php";

class TimeoutException extends KondutoException {

    public $message = "Communication timeout for Konduto API";
    public function __construct() {}
}