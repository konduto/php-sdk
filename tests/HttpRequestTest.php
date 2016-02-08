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

//    function test_getJson() {
//        $req = new HttpRequest("get", "/uri");
//        $headers = $req->getHeaders();
//        $this->assertArrayHasKey("X-Requested-With", $headers);
//        $this->assertEquals($headers["X-Requested-With"], "Konduto php-sdk " . Parameters::SDK_VERSION);
//    }
}