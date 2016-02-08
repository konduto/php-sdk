<?php namespace Konduto\Exceptions;

use Konduto\Core\HttpResponse;

class OperationNotAllowedException extends KondutoException {
    static protected $httpCode = HttpResponse::HTTP_STATUS_FORBIDDEN;
}