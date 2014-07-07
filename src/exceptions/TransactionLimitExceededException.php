<?php
namespace konduto\exceptions;
require_once "KondutoException.php";

/**
 * Response not implemented.
 */

class TransactionLimitExceededException extends KondutoException {

    public $message = "The number of transactions allowed for this API key was exceeded. Please, contact Konduto team at support@konduto.com";

    public function __construct() {}
}