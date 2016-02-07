<?php namespace Konduto\Core;

use Konduto\Exceptions as Exceptions;

class HttpResponse {

    const HTTP_STATUS_OK = 200;
    const HTTP_STATUS_UNAUTHORIZED = 401;
    const HTTP_STATUS_FORBIDDEN = 403;
    const HTTP_STATUS_NOT_FOUND = 404;
    const HTTP_STATUS_INTERNAL_ERROR = 500;

    public $body;
    public $httpStatus;
    public $curlCode;

    public function __construct(CurlSession $curlSession) {
        $this->httpStatus = $curlSession->getInfo(CURLINFO_HTTP_CODE);
        $this->curlCode = $curlSession->getErrorNo();
        $this->body = $curlSession->getResponseBody();
    }

    public function checkResponse() {
        // Treat curl errors
        switch ($this->curlCode) {
            case CURLE_OK:  // No errors.
                break;
            case CURLE_OPERATION_TIMEOUTED:
                throw new Exceptions\TimeoutException();
                break;
            case CURLE_COULDNT_CONNECT:
            default:
                throw new Exceptions\CommunicationErrorException($this->curlCode);
        }

        // Treat http status code
        switch ($this->httpStatus) {
            case self::HTTP_STATUS_INTERNAL_ERROR:
                throw new Exceptions\KondutoAPIErrorException();
                break;
            case self::HTTP_STATUS_UNAUTHORIZED:
                throw new Exceptions\InvalidAPIKeyException("");
                break;
            case self::HTTP_STATUS_FORBIDDEN:
                throw new Exceptions\OperationNotAllowedException();
                break;
            default: // Do nothing.
        }
    }

    public function getBody() {
        return $this->body;
    }

    public function getBodyAssocArray() {
        return json_decode($this->getBody(), true);
    }

    public function getCurlCode() {
        return $this->curlCode;
    }

    public function getHttpStatus() {
        return $this->httpStatus;
    }
}