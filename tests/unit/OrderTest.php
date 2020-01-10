<?php namespace Konduto\Tests;

use Konduto\Models\Order;

class OrderTest extends \PHPUnit_Framework_TestCase {

    function test_orderPayments() {
        $order = new Order(array(
            "id" => uniqid(),
            "total_amount" => 100.01,
            "customer" => array(
                "id" => "28372",
                "tax_id" => "12345678909",
                "email" => "jsilva@exemplo.com.br"
            ),
            "payment" => array(
                array(
                    "type" => "credit",
                    "bin" => "123456",
                    "last4" => "0987",
                    "amount" => 99.00
                ),
                array(
                    "type" => "voucher",
                    "description" => "Coupon A",
                    "amount" => 1.01
                )
            )
        ));
        $payment = $order->getPayment();
        $this->assertInstanceOf('Konduto\Models\CreditCard', $payment[0]);
        $this->assertInstanceOf('Konduto\Models\Payment', $payment[1]);
        $this->assertEquals(1.01, $payment[1]->getAmount());
        $this->assertEquals("Coupon A", $payment[1]->getDescription());
        $this->assertEquals(99.0, $payment[0]->getAmount());
    }
}