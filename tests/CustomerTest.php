<?php namespace Konduto\Tests;

use Konduto\Models\Customer as Customer;

class CustomerTest extends \PHPUnit_Framework_TestCase {

    function test_construct1() {
        $customer = new Customer();
        $customer->setId("customer001");
        $customer->setEmail("customer@email.com");
        $customer->setCreatedAt('2018-12-23');
        $cusArr = $customer->toJsonArray();
        $this->assertEquals(array(
            "id" => "customer001",
            "email" => "customer@email.com",
            "created_at" => '2018-12-23'
        ), $cusArr);
        $this->assertInstanceOf('DateTime', $customer->getCreatedAt());
    }

    function test_construct2() {
        $customer = new Customer(array(
            "id" => "customer002",
            "email" => "customer@email.com",
            "tax_id" => "tax002",
            "new" => true
        ));
        $cusArr = $customer->toJsonArray();
        $this->assertEquals(array(
            "id" => "customer002",
            "email" => "customer@email.com",
            "tax_id" => "tax002",
            "new" => true
        ), $cusArr);
    }
}