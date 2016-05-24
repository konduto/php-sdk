<?php namespace Konduto\Exceptions;

use Konduto\Core\HttpResponse;

class OperationNotAllowedException extends HttpCodedException {
    static protected $httpCode = HttpResponse::HTTP_STATUS_FORBIDDEN;
}