<?php namespace Konduto\Exceptions;

use Konduto\Core\HttpResponse;

class TransactionLimitExceededException extends HttpCodedException {
    static protected $httpCode = HttpResponse::HTTP_STATUS_UNAUTHORIZED;
}
