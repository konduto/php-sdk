<?php namespace Konduto\Exceptions;

use Konduto\Core\HttpResponse;

class BadRequestError extends KondutoException {
    static protected $httpCode = HttpResponse::HTTP_STATUS_BAD_REQUEST;
}