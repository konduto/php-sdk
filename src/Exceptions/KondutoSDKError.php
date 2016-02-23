<?php namespace Konduto\Exceptions;

class KondutoSDKError extends KondutoException {

    public $message = "Konduto SDK library encountered an internal error. Please, contact bugs@konduto.com";
    public function __construct() {}
}
