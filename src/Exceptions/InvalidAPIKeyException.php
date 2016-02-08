<?php namespace Konduto\Exceptions;

use Konduto\Core\HttpResponse;

class InvalidAPIKeyException extends KondutoException {
    static protected $httpCode = HttpResponse::HTTP_STATUS_UNAUTHORIZED;
}
