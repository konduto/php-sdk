<?php namespace Konduto\Tests\Integration;

use Konduto\Core\Konduto;
use Konduto\Models\Order;

class ExceptionsTest extends \PHPUnit_Framework_TestCase {

    public $uniqueId = '';

    public function setUp() {
        Konduto::setApiKey('T738D516F09CAB3A2C1EE');
    }

    function test_nonexistentKey() {
        $nonexistentKey = "T0000000AAAAAAEEEFEEE";
        $this->setExpectedException('Konduto\Exceptions\InvalidAPIKeyException');
        Konduto::setApiKey($nonexistentKey);
        Konduto::analyze($this->buildOrder());
    }

    function test_duplicateOrder() {
        $order = $this->buildOrder();
        $this->setExpectedException('Konduto\Exceptions\DuplicateOrderException');
        Konduto::analyze($order);
        Konduto::analyze($order);
    }

    function test_invalidAPIKey() {
        $this->setExpectedException('Konduto\Exceptions\InvalidAPIKeyException');
        Konduto::setApiKey("xxx");
    }

    function test_orderNotFound() {
        $this->setExpectedException('Konduto\Exceptions\OrderNotFoundException');
        Konduto::getOrder("nonexistent-order-123123");
    }

    function buildOrder($id = null, array $info = array()) {
        $id = $id == null ? "PhpSdkOrder-" . uniqid() : $id;
        $order = new Order(array_replace(array(
            "id"=> $id,
            "total_amount"=> 100.01,  // Result approve in test env
            "customer"=> array(
                "id"=> "28372",
                "name"=> "Júlia da Silva",
                "email"=> "jsilva@exemplo.com.br"
            )
        ), $info));
        return $order;
    }
}
