<?php namespace Konduto\Core;

use Konduto\Exceptions as Exceptions;

class HttpResponse {

    const HTTP_STATUS_OK = 200;
    const HTTP_STATUS_BAD_REQUEST = 400;
    const HTTP_STATUS_UNAUTHORIZED = 401;
    const HTTP_STATUS_FORBIDDEN = 403;
    const HTTP_STATUS_NOT_FOUND = 404;
    const HTTP_TOO_MANY_REQUESTS = 429;
    const HTTP_STATUS_INTERNAL_ERROR = 500;

    protected $body;
    protected $jsonBody = null;
    protected $httpStatus;
    protected $curlCode;

    public function __construct(CurlSession $openCurlSession) {
        $this->httpStatus = $openCurlSession->getInfo(CURLINFO_HTTP_CODE);
        $this->curlCode = $openCurlSession->getErrorNo();
        $this->body = $openCurlSession->getResponseBody();
    }

    public function checkCurlResponse() {
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
    }

    public function getBody() {
        return $this->body;
    }

    public function getBodyAsJson() {
        if (is_null($this->jsonBody))
            $this->jsonBody = json_decode($this->getBody(), true);
        return $this->jsonBody;
    }

    public function getCurlCode() {
        return $this->curlCode;
    }

    public function getHttpStatus() {
        return $this->httpStatus;
    }

    public function isOk() {
        return $this->httpStatus == self::HTTP_STATUS_OK;
    }
}