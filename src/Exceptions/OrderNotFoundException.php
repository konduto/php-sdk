<?php namespace Konduto\Exceptions;

use Konduto\Core\HttpResponse;

class OrderNotFoundException extends KondutoException {
    static protected $httpCode = HttpResponse::HTTP_STATUS_NOT_FOUND;
}
