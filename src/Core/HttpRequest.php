<?php namespace Konduto\Core;

use \Konduto\Exceptions as Exceptions;
use \Konduto\Params as Parameters;

/**
 * API specific control methods
 *
 * This class describes details of the API functioning, such as use of curl library.
 * It provides auxiliary methods to be used by the public methods.
 * All its methods are protected.
 */
class HttpRequest {

    public static $availableMethods = array("get", "post", "put");

    protected $method;
    protected $uri;
    protected $verifySSL;
    protected $headers = array();
    protected $body = "";

    /**
     * HttpRequest constructor.
     * @param $method string one of the $available_methods
     * @param $uri string uri to be requested
     * @param $verifySSL bool
     */
    public function __construct($method, $uri, $verifySSL=true) {
        if (!in_array($method, self::$availableMethods))
            throw new \InvalidArgumentException("Method must be one of: " . implode(", ", self::$availableMethods));
        $this->uri = $uri;
        $this->method = $method;
        $this->verifySSL = $verifySSL;
        $this->setHeader("X-Requested-With", "Konduto php-sdk " . Parameters::SDK_VERSION);
    }

    public function setBasicAuthorization($user, $password=null) {
        $base64 = base64_encode($password ? "$user:$password" : $user);
        $this->setHeader("Authorization", "Basic {$base64}");
    }

    public function setHeader($header, $value) {
        $this->headers[$header] = $value;
    }

    public function setBody($body) {
        $this->body = $body;
        $this->setHeader("Content-length", strlen($body));
    }

    public function setBodyAsJson(array $body) {
        $bodyStr = json_encode($body);
        $this->setBody($bodyStr);
        $this->setContentType("application/json; charset=utf-8");
    }

    public function setContentType($contentType) {
        $this->setHeader("Content-type", $contentType);
    }

    public function send() {
        $curlSession = new CurlSession($this->uri);

        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => Parameters::API_TIMEOUT,
            CURLOPT_FAILONERROR    => false,
            CURLOPT_HTTPHEADER     => $this->buildHeadersArray(),
            CURLOPT_SSL_VERIFYPEER => $this->verifySSL
        );

        switch ($this->method) {
            case "post":
                $options[CURLOPT_POST] = true;
                $options[CURLOPT_POSTFIELDS] = $this->getBody();
                break;
            case "put":
                $options[CURLOPT_CUSTOMREQUEST] = 'PUT';
                $options[CURLOPT_POSTFIELDS] = $this->getBody();
                break;
            case "get":
            default:  // GET is curl's default method: Do nothing.
        }

        $curlSession->setOptionsArray($options);
        $curlSession->execute();
        $response = new HttpResponse($curlSession);
        $curlSession->close();

        return $response;
    }

    private function buildHeadersArray() {
        $headers = $this->headers;
        $headersKeys = array_keys($headers);
        $headersArray = array_map(function ($key) use ($headers) {return "$key: $headers[$key]";},
            $headersKeys);
        return $headersArray;
    }

    public function getMethod() {
        return $this->method;
    }

    public function getBody() {
        return $this->body;
    }

    public function getUri() {
        return $this->uri;
    }

    public function getHeaders() {
        return $this->headers;
    }
}

