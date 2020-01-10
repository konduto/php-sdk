<?php namespace Konduto\Tests;

use Konduto\Models\Payment;
use Konduto\Models\CreditCard;
use Konduto\Models\Boleto;

class PaymentTest extends \PHPUnit_Framework_TestCase {

    function test_cc1() {
        $cc = Payment::build(array(
            "type" => "credit",
            "bin" => "531234",
            "last4" => "1123",
            "expiration_date" => '122018'
        ));
        $ccArr = $cc->toJsonArray();
        $this->assertInstanceOf('Konduto\Models\CreditCard', $cc);
        $this->assertEquals((array(
            "type" => "credit",
            "bin" => "531234",
            "last4" => "1123",
            "expiration_date" => "122018"
        )), $ccArr);
    }

    function test_boleto1() {
        $bol = Payment::build(array(
            "type" => "boleto",
            "expiration_date" => new \DateTime('2018-12-23 02:30:00', new \DateTimeZone('UTC'))
        ));
        $ccArr = $bol->toJsonArray();
        $this->assertInstanceOf('Konduto\Models\Boleto', $bol);
        $this->assertEquals((array(
            "type" => "boleto",
            "expiration_date" => "2018-12-23"
        )), $ccArr);
    }
    
    function test_voucher() {
        $voucher = Payment::build(array(
            "type" => "voucher",
            "description" => "10% discount",
            "amount" => 13.90
        ));
        $arr = $voucher->toJsonArray();
        $this->assertInstanceOf('Konduto\Models\Payment', $voucher);
        $this->assertEquals(array(
            "type" => $voucher->getType(),
            "description" => $voucher->getDescription(),
            "amount" => $voucher->getAmount()
        ), $arr);
    }
}