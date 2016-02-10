<?php namespace Konduto\Exceptions;

use Konduto\Core\HttpResponse;

class KondutoAPIErrorException extends HttpCodedException {
    static protected $httpCode = HttpResponse::HTTP_STATUS_INTERNAL_ERROR;
}
