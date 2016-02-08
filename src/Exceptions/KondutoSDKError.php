<?php namespace Konduto\Exceptions;

class KondutoSDKError extends KondutoException {
    public function __construct() {
        parent::__construct("Konduto SDK library encountered an internal error" .
                            "Please, contact bugs@konduto.com");
    }
}
