<?php namespace Konduto\Exceptions;

use Konduto\Core\HttpResponse;

class OrderNotFoundException extends HttpCodedException {
    static protected $httpCode = HttpResponse::HTTP_STATUS_NOT_FOUND;
}
