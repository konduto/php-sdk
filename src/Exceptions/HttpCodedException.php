<?php namespace Konduto\Exceptions;

use Konduto\Core\HttpResponse;

abstract class HttpCodedException extends KondutoException {

    static public function getException($code, $body) {
        switch ($code) {
            case HttpResponse::HTTP_STATUS_BAD_REQUEST:
                return new BadRequestError($body);
            case HttpResponse::HTTP_STATUS_UNAUTHORIZED:
                return new InvalidAPIKeyException($body);
            case HttpResponse::HTTP_STATUS_INTERNAL_ERROR:
                return new KondutoAPIErrorException($body);
            case HttpResponse::HTTP_STATUS_NOT_FOUND:
                return new OrderNotFoundException($body);
            case HttpResponse::HTTP_TOO_MANY_REQUESTS:
                return new TransactionLimitExceededException($body);
            case HttpResponse::HTTP_STATUS_FORBIDDEN:
                return new OperationNotAllowedException($body);
            case HttpResponse::HTTP_STATUS_OK:
                return new UnexpectedResponseException($body);
        }
        return new KondutoException($body);
    }
}