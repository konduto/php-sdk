<?php namespace Konduto\exceptions;

class OperationNotAllowedException extends KondutoException {

    public $message = "The operation being performed is not allowed for the current API key. Please, contact support@Konduto.com";

    public function __construct() {}
}