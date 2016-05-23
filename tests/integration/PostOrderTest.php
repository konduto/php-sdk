<?php namespace Konduto\Tests;

use Konduto\Core\Konduto;
use Konduto\Models\Order;

class PostOrderTest extends \PHPUnit_Framework_TestCase {

    public $uniqueId;

    public static function setUpBeforeClass() {
        Konduto::setApiKey("T738D516F09CAB3A2C1EE");
    }

    function test_simplePostApprove() {
        $order = $this->buildOrder1(array("total_amount" => 100));
        $order = Konduto::analyze($order);
        $this->assertOrderResponse($order, 'APPROVE', 0);
    }

    function test_simplePostReview() {
        $order = $this->buildOrder1(array("total_amount" => 100.31));
        $order = Konduto::analyze($order);
        $this->assertOrderResponse($order, 'REVIEW', 0.31);
    }

    function test_simplePostDecline() {
        $order = $this->buildOrder1(array("total_amount" => 100.61));
        $order = Konduto::analyze($order);
        $this->assertOrderResponse($order, 'DECLINE', 0.61);
    }

    function test_simplePostBadRequest() {
        $order = $this->buildOrder1(array("total_amount" => "bad_value"));
        $this->setExpectedException('Konduto\Exceptions\BadRequestError');
        Konduto::analyze($order);
    }

    function test_assertAllFields() {
        $order = $this->buildOrder1();
        $order = Konduto::analyze($order);
        $this->assertOrder1Fields($order);
    }

    function buildOrder1(array $info = array()) {
        $this->uniqueId = "PhpSdkOrder1-" . uniqid();
        $order = new Order(array_replace(array(
            "id" => $this->uniqueId,
            "visitor" => "da39a3ee5e6b4b0d3255bfef95601890afd80709",
            "total_amount" => 100.01,  // Result approve in test env
            "shipping_amount" => 20.00,
            "tax_amount" => 3.45,
            "currency" => "BRL",
            "installments" => 2,
            "ip" => "189.68.156.100",
            "customer" => array(
                "id" => "28372",
                "name" => "Júlia da Silva",
                "tax_id" => "12345678909",
                "dob" => "1970-12-25",
                "phone1" => "11-1234-5678",
                "phone2" => "21-2143-6578",
                "email" => "jsilva@exemplo.com.br",
                "created_at" => "2010-12-25",
                "new" => false,
                "vip" => false
            )
        ), $info));
        return $order;
    }

    function assertOrderResponse(Order $order, $recommendation, $score) {
        $this->assertEquals($recommendation, $order->getRecommendation());
        $this->assertEquals($score, $order->getScore());
        $this->assertInstanceOf('Konduto\Models\Device', $order->getDevice());
        $this->assertInstanceOf('Konduto\Models\Geolocation', $order->getGeolocation());
    }

    function assertOrder1Fields(Order $order) {
        $this->assertEquals($this->uniqueId, $order->getId());
        $this->assertEquals("da39a3ee5e6b4b0d3255bfef95601890afd80709", $order->getVisitor());
        $this->assertEquals(100.01, $order->getTotalAmount());
        $this->assertEquals(20.0, $order->getShippingAmount());
        $this->assertEquals(3.45, $order->getTaxAmount());
        $this->assertEquals("BRL", $order->getCurrency());
        $this->assertEquals(2, $order->getInstallments());
        $this->assertEquals("189.68.156.100", $order->getIp());
        $this->assertInstanceOf('Konduto\Models\Customer', $order->getCustomer());
        $customer = $order->getCustomer();
        $this->assertEquals("28372", $customer->getId());
        $this->assertEquals("Júlia da Silva", $customer->getName());
        $this->assertEquals("12345678909", $customer->getTaxId());
        $this->assertEquals(new \DateTime("1970-12-25"), $customer->getDob());
        $this->assertEquals("11-1234-5678", $customer->getPhone1());
        $this->assertEquals("21-2143-6578", $customer->getPhone2());
        $this->assertEquals("jsilva@exemplo.com.br", $customer->getEmail());
        $this->assertEquals(new \DateTime("2010-12-25"), $customer->getCreatedAt());
        $this->assertFalse($customer->getNew());
        $this->assertFalse($customer->getVip());
        $this->assertOrderResponse($order, "APPROVE", 0.01);
    }
}
