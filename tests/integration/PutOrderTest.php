<?php namespace Konduto\Tests\Integration;

use Konduto\Core\Konduto;
use Konduto\Models\Order;

class PutOrderTest extends \PHPUnit_Framework_TestCase {

    public $uniqueId;

    public static function setUpBeforeClass() {
        Konduto::setApiKey("T738D516F09CAB3A2C1EE");
    }

    function test_approve() {
        $order = $this->buildOrderSimple(array("total_amount" => 100.30));
        $order = $this->analyzeUpdateGet($order, Order::STATUS_APPROVED, "know the guy");
        $this->assertEquals(Order::STATUS_APPROVED, $order->getStatus(), "", 0, 0, false, true);
    }

    function test_notAuthorized() {
        $order = $this->buildOrderSimple(array("total_amount" => 100.30));
        $order = $this->analyzeUpdateGet($order, Order::STATUS_NOT_AUTHORIZED, "oops");
        $this->assertEquals(Order::STATUS_NOT_AUTHORIZED, $order->getStatus(), "", 0, 0, false, true);
    }

    function analyzeUpdateGet(Order $order, $status, $comment) {
        $order = Konduto::analyze($order);
        $order = Konduto::getOrder($order->getId());
        $this->assertEquals(Order::STATUS_PENDING, $order->getStatus(), "Be sure it is pending first");
        Konduto::updateOrderStatus($order->getId(), $status, $comment);
        return Konduto::getOrder($order->getId());
    }

    /**
     * Builds a simple order with a Customer
     * @param array $info array to merge onto array passed to constructor
     * @return Order
     */
    function buildOrderSimple(array $info = array()) {
        $this->uniqueId = "PhpSdkOrderPut-" . uniqid();
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
}
