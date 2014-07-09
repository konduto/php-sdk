<?php namespace Konduto\Exceptions;

class CommunicationErrorException extends KondutoException {

    public function __construct($curl_errno) {
        $message = "Communication error (curl error code = {$curl_errno})";
        parent::__construct($message);
    }
}