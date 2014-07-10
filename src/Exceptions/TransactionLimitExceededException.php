<?php namespace Konduto\Exceptions;

/**
 * Response not implemented.
 */

class TransactionLimitExceededException extends KondutoException {

    public $message = "The number of transactions allowed for this API key was exceeded. Please, contact Konduto team at support@Konduto.com";

    public function __construct() {}
}