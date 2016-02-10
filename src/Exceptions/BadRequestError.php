<?php namespace Konduto\Exceptions;

use Konduto\Core\HttpResponse;

class BadRequestError extends HttpCodedException {
    static protected $httpCode = HttpResponse::HTTP_STATUS_BAD_REQUEST;
}