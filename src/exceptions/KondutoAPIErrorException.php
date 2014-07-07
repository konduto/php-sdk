<?php
namespace konduto\exceptions;
require_once "KondutoException.php";

class KondutoAPIErrorException extends KondutoException {

    public $message = "API internal error. Please, contact bugs@konduto.com";
    public function __construct() {}
}