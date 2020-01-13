<?php namespace Konduto\Tests;

use Konduto\Models\Order;

class OrderTest extends \PHPUnit_Framework_TestCase {

    function test_orderParse() {
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
            ),
            "bureaux_queries" => array(
                array(
                    "service" => "emailage",
                    "response" => array(
                        "advice" => "Lower Fraud Risk",
                        "email" => "adriano.0123456abc@gmail.com",
                        "email_country" => "US",
                        "email_domain" => "gmail.com",
                        "email_domain_age" => "1995-08-13T04:00:00Z",
                        "email_domain_category" => "Webmail",
                        "email_domain_company" => "Google",
                        "email_domain_country" => "United States",
                        "email_domain_exists" => true,
                        "email_domain_is_corporate" => false,
                        "email_domain_other_info" => "Valid Webmail Domain from United States"
                    )
                ),
                array(
                    "service" => "whitepages",
                    "response" => array(
                        "email" => "adriano.0123456abc@gmail.com",
                        "email_domain" => "gmail.com"
                    )
                )
            ),
            "triggered_rules" => array(
                array(
                    "name" => "Cartão com BIN do Canadá", 
                    "decision" => "decline"
                )
            ),
            "triggered_decision_list" => array(
                array(
                    "type" => "zip", 
                    "trigger" => "shipping_zip", 
                    "decision" => "review"
                ),
                array(
                    "type" => "email", 
                    "trigger" => "email", 
                    "decision" => "decline"
                )
            )
        ));
        $payment = $order->getPayment();
        $bureauxQueries = $order->getBureauxQueries();
        $triggeredRules = $order->getTriggeredRules();
        $triggeredDecList = $order->getTriggeredDecisionList();
        $this->assertInstanceOf('Konduto\Models\CreditCard', $payment[0]);
        $this->assertInstanceOf('Konduto\Models\Payment', $payment[1]);
        $this->assertInstanceOf('Konduto\Models\BureauxQuery', $bureauxQueries[0]);
        $this->assertEquals(1.01, $payment[1]->getAmount());
        $this->assertEquals("Coupon A", $payment[1]->getDescription());
        $this->assertEquals(99.0, $payment[0]->getAmount());
        $this->assertEquals(2, count($bureauxQueries));
        $this->assertEquals("emailage", $bureauxQueries[0]->getService());
        $this->assertEquals(11, count($bureauxQueries[0]->getResponse()));
        $this->assertEquals("Lower Fraud Risk", $bureauxQueries[0]->getResponse()["advice"]);
        $this->assertEquals("whitepages", $bureauxQueries[1]->getService());
        $this->assertEquals(2, count($bureauxQueries[1]->getResponse()));
        $this->assertEquals("gmail.com", $bureauxQueries[1]->getResponse()["email_domain"]);
        $this->assertEquals("Cartão com BIN do Canadá", $triggeredRules[0]->getName());
        $this->assertEquals("decline", $triggeredRules[0]->getDecision());
        $this->assertEquals(2, count($triggeredDecList));
        $this->assertEquals("shipping_zip", $triggeredDecList[0]->getTrigger());
        $this->assertEquals("decline", $triggeredDecList[1]->getDecision());
    }
}