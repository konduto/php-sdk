<?php namespace Konduto\Exceptions;

class KondutoAPIErrorException extends KondutoException {

    public $message = "API internal error. Please, contact bugs@Konduto.com";
    public function __construct() {}
}