<?php namespace Konduto\Tests;

use Konduto\Models\Payment;
use Konduto\Models\CreditCard;
use Konduto\Models\Boleto;

class PaymentTest extends \PHPUnit\Framework\TestCase {

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

    function test_pix() {
        $pix = Payment::build(array(
            "type" => "pix",
            "description" => "12% discount",
            "amount" => 13.90
        ));
        $arr = $pix->toJsonArray();
        $this->assertInstanceOf('Konduto\Models\Payment', $pix);
        $this->assertEquals(array(
            "type" => $pix->getType(),
            "description" => $pix->getDescription(),
            "amount" => $pix->getAmount()
        ), $arr);
    }

    function test_balance() {
        $balance = Payment::build(array(
            "type" => "balance",
            "amount" => 30.00
        ));

        $this->assertInstanceOf('Konduto\Models\Payment', $balance);
        $this->assertEquals('balance', $balance->getType());
    }

    function test_creditCardExtraFields() {
        $cc = Payment::build(array(
            "type" => "credit",
            "bin" => "490172",
            "last4" => "0012",
            "expiration_date" => "072015",
            "status" => "approved",
            "tax_id" => "11111111111",
            "cvv_result" => "Y",
            "avs_result" => "X",
            "sha1" => "3da541559918a808c2402bba5012f6c60b27661c",
            "name" => "Comprador",
            "holder" => "Titular",
            "mcc" => 1234,
            "mid" => "mid-1",
            "3ds_id" => "3ds-abc",
            "merchant_tax_id" => "12345678000190",
            "voucher_type" => "gift"
        ));

        $arr = $cc->toJsonArray();
        $this->assertEquals("11111111111", $arr["tax_id"]);
        $this->assertEquals("Y", $arr["cvv_result"]);
        $this->assertEquals("X", $arr["avs_result"]);
        $this->assertEquals("3ds-abc", $arr["3ds_id"]);
        $this->assertEquals("12345678000190", $arr["merchant_tax_id"]);
    }
}