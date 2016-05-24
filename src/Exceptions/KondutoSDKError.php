<?php namespace Konduto\Exceptions;

class KondutoSDKError extends KondutoException {
<<<<<<< HEAD

    public $message = "Konduto SDK library encountered an internal error. Please, contact bugs@konduto.com";
    public function __construct() {}
=======
    public function __construct() {
        parent::__construct("Konduto SDK library encountered an internal error" .
                            "Please, contact bugs@konduto.com");
    }
>>>>>>> v2-beta
}
