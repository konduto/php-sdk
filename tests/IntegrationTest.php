<?php namespace Konduto\Tests;

use Konduto\Core\Konduto;
use Konduto\Exceptions\BadRequestError;
use Konduto\Models\Order;

class IntegrationTest extends \PHPUnit_Framework_TestCase {

    public $testKey = 'T738D516F09CAB3A2C1EE';
    public $uniqueId = '';

    function test_simplePostApprove() {
        $this->uniqueId = "PhpSdkOrder-" . uniqid();
        $order = $this->buildOrderWith($this->uniqueId, array("total_amount" => 100));
        Konduto::setApiKey($this->testKey);
        $order = Konduto::analyze($order);
        $this->assertOrderResponse($order, 'APPROVE', 0);
    }

    function test_simplePostReview() {
        $this->uniqueId = "PhpSdkOrder-" . uniqid();
        $order = $this->buildOrderWith($this->uniqueId, array("total_amount" => 100.31));
        Konduto::setApiKey($this->testKey);
        $order = Konduto::analyze($order);
        $this->assertOrderResponse($order, 'REVIEW', 0.31);
    }

    function test_simplePostDecline() {
        $this->uniqueId = "PhpSdkOrder-" . uniqid();
        $order = $this->buildOrderWith($this->uniqueId, array("total_amount" => 100.61));
        Konduto::setApiKey($this->testKey);
        $order = Konduto::analyze($order);
        $this->assertOrderResponse($order, 'DECLINE', 0.61);
    }

    function test_simplePostBadRequest() {
        $this->uniqueId = "PhpSdkOrder-" . uniqid();
        $order = $this->buildOrderWith($this->uniqueId, array("total_amount" => "bad_total_amount"));
        Konduto::setApiKey($this->testKey);
        $this->setExpectedException('Konduto\Exceptions\BadRequestError');
        $order = Konduto::analyze($order);
        $this->getExpectedException();
    }

    protected function buildOrderWith($id, array $info = array()) {
        $order = new Order(array_replace(array(
            "id"=> $id,
            "visitor"=> "da39a3ee5e6b4b0d3255bfef95601890afd80709",
            "total_amount"=> 100.01,  // Result approve in test env
            "shipping_amount"=> 20.00,
            "tax_amount"=> 3.45,
            "currency"=> "BRL",
            "installments"=> 2,
            "ip"=> "189.68.156.100",
            "customer"=> array(
                "id"=> "28372",
                "name"=> "Júlia da Silva",
                "tax_id"=> "12345678909",
                "dob"=> "1970-12-25",
                "phone1"=> "11-1234-5678",
                "phone2"=> "21-2143-6578",
                "email"=> "jsilva@exemplo.com.br",
                "created_at"=> "2010-12-25",
                "new"=> false,
                "vip"=> false
            )
        ), $info));
        return $order;
    }

    protected function assertOrderResponse(Order $order, $recommendation, $score) {
        $this->assertEquals($recommendation, $order->getRecommendation());
        $this->assertEquals($score, $order->getScore());
        $this->assertInstanceOf('Konduto\Models\Device', $order->getDevice());
        $this->assertInstanceOf('Konduto\Models\Geolocation', $order->getGeolocation());
    }
}