<?php namespace Konduto\exceptions;

class OrderNotFoundException extends KondutoException {

    public function __construct($order_id) {
        $message = "Order '$order_id' not found.";
        parent::__construct($message);
    }
}