<?php namespace Konduto\Tests;

use Konduto\Models\PointOfSale as PointOfSale;

class PointOfSaleTest extends \PHPUnit_Framework_TestCase {

    function test_setters_getters() {
        $pointOfSale = new PointOfSale();
        $pointOfSale->setId("51")
            ->setName("Capital Representações")
            ->setLat(-23.5677666)
            ->setLon(-46.6487763)
            ->setAddress("Rua Dez de Abril, 23")
            ->setCity("São Paulo")
            ->setState("SP")
            ->setZip("01001-001")
            ->setCountry("BR");
        $this->assertEquals("51", $pointOfSale->getId());
        $this->assertEquals("Capital Representações", $pointOfSale->getName());
        $this->assertEquals(-23.5677666, $pointOfSale->getLat());
        $this->assertEquals(-46.6487763, $pointOfSale->getLon());
        $this->assertEquals("Rua Dez de Abril, 23", $pointOfSale->getAddress());
        $this->assertEquals("São Paulo", $pointOfSale->getCity());
        $this->assertEquals("SP", $pointOfSale->getState());
        $this->assertEquals("01001-001", $pointOfSale->getZip());
        $this->assertEquals("BR", $pointOfSale->getCountry());
    }
}