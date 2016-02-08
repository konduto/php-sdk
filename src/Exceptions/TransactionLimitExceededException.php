<?php namespace Konduto\Exceptions;

use Konduto\Core\HttpResponse;

class TransactionLimitExceededException extends KondutoException {

    static protected $httpCode = HttpResponse::HTTP_STATUS_UNAUTHORIZED;
}
