<?php
namespace konduto\exceptions;
require_once "KondutoException.php";

class DuplicateOrderException extends KondutoException {

    public function __construct($order_id) {
        $message = "An order with id '$order_id' already exists";
        parent::__construct($message);
    }
}