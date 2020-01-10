<?php namespace Konduto\Tests;

use Konduto\Core\HttpRequest as HttpRequest;
use Konduto\Params as Parameters;

class HttpRequestTest extends \PHPUnit_Framework_TestCase {

    function test_constructOk() {
        $req = new HttpRequest("post", "/uri");
        $this->assertEquals("post", $req->getMethod());
        $this->assertEquals("/uri", $req->getUri());
    }

    function test_constructFail() {
        $this->setExpectedException("InvalidArgumentException");
        $req = new HttpRequest("nana", "/uri");
        $this->getExpectedException();
    }

    function test_authorization() {
        $req = new HttpRequest("get", "/uri");
        $req->setBasicAuthorization("T0123456789918273645");
        $headers = $req->getHeaders();
        $this->assertArrayHasKey("Authorization", $headers);
        $this->assertEquals($headers["Authorization"], "Basic VDAxMjM0NTY3ODk5MTgyNzM2NDU=");
    }

    function test_xRequestedWith() {
        $req = new HttpRequest("get", "/uri");
        $headers = $req->getHeaders();
        $this->assertArrayHasKey("X-Requested-With", $headers);
        $this->assertEquals($headers["X-Requested-With"], "Konduto php-sdk " . Parameters::SDK_VERSION);
    }

    function test_setJsonBody() {
        $req = new HttpRequest("get", "/uri");
        $aJson = array("key1" => "value1", "key2" => 2);
        $headers = $req->getHeaders();
        $this->assertArrayNotHasKey("Content-type", $headers);
        $req->setBodyAsJson($aJson);
        $headers = $req->getHeaders();
        $this->assertEquals("{\"key1\":\"value1\",\"key2\":2}", $req->getBody());
        $this->assertArrayHasKey("Content-type", $headers);
        $this->assertEquals("application/json; charset=utf-8", $headers["Content-type"]);
    }

    function test_sendGet() {
        $req = new HttpRequest("get", "/uri");
        $req->setBasicAuthorization("T0123456789918273645");
        $expectedHeaders = array('X-Requested-With: Konduto php-sdk ' . Parameters::SDK_VERSION,
            'Authorization: Basic VDAxMjM0NTY3ODk5MTgyNzM2NDU=');
        $req->setCurlSession($this->buildMockCurlSession('/uri', $expectedHeaders));
        $response = $req->send();
        $this->assertInstanceOf('Konduto\Core\HttpResponse', $response);
    }

    function test_sendPost() {
        $req = new HttpRequest("post", "/uri");
        $req->setBasicAuthorization("T0123456789918273645");
        $expectedHeaders = array('X-Requested-With: Konduto php-sdk ' . Parameters::SDK_VERSION,
            'Authorization: Basic VDAxMjM0NTY3ODk5MTgyNzM2NDU=');
        $expectedAddOptions = array(CURLOPT_POST => true, CURLOPT_POSTFIELDS => "");
        $req->setCurlSession($this->buildMockCurlSession('/uri', $expectedHeaders, $expectedAddOptions));
        $response = $req->send();
        $this->assertInstanceOf('Konduto\Core\HttpResponse', $response);
    }

    function test_sendPut() {
        $req = new HttpRequest("put", "/uri");
        $req->setBasicAuthorization("T0123456789918273645");
        $req->setBodyAsJson(array("key"=>"val"));
        $expectedBody = "{\"key\":\"val\"}";
        $expectedContentLength = strlen($expectedBody);
        $expectedHeaders = array('X-Requested-With: Konduto php-sdk '.Parameters::SDK_VERSION,
            'Authorization: Basic VDAxMjM0NTY3ODk5MTgyNzM2NDU=', 'Content-length: '.$expectedContentLength ,
            'Content-type: application/json; charset=utf-8');
        $expectedAddOptions = array(CURLOPT_CUSTOMREQUEST => 'PUT', CURLOPT_POSTFIELDS => "{\"key\":\"val\"}");
        $req->setCurlSession($this->buildMockCurlSession('/uri', $expectedHeaders, $expectedAddOptions));
        $response = $req->send();
        $this->assertInstanceOf('Konduto\Core\HttpResponse', $response);
    }

    function buildMockCurlSession($uri, $headers, $additionalOpts=array()) {
        $curlSess = $this->getMockBuilder('Konduto\Core\CurlSession')
            ->setMethods(array('setOptionsArray', 'execute', 'close'))
            ->setConstructorArgs(array($uri))
            ->getMock();

        $optionsArray = array_replace(array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => Parameters::API_TIMEOUT,
            CURLOPT_FAILONERROR    => false,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_SSL_VERIFYPEER => true),
            $additionalOpts);

        $curlSess->expects($this->once())
            ->method('setOptionsArray')
            ->with($this->identicalTo($optionsArray));

        $curlSess->expects($this->once())
            ->method('execute');

        $curlSess->expects($this->once())
            ->method('close');

        return $curlSess;
    }
}