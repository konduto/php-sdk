<?php
namespace konduto\exceptions;
require_once "KondutoException.php";

class OrderNotFoundException extends KondutoException {

    public function __construct($order_id) {
        $message = "Order '$order_id' not found.";
        parent::__construct($message);
    }
}