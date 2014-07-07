<?php
namespace konduto\exceptions;
require_once "KondutoException.php";

class InvalidOrderException extends KondutoException {

    public function __construct($fields) {
        $field_array = is_array($fields) ? array_keys($fields) : [$fields];
        $message = "'" . implode("', '", $field_array) . "' " . (count($field_array) == 1 ? "field is" : "fields are") . " absent or not correctly provided";
        parent::__construct($message);
    }
}