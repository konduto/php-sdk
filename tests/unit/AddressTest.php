<?php namespace Konduto\Tests;

use Konduto\Models\Address as Address;

class AddressTest extends \PHPUnit_Framework_TestCase {

    function test_setters_getters() {
        $address = new Address();
        $address->setName("Mario Peruzzi")
            ->setAddress1("Rua das Palmeiras, 454")
            ->setAddress2("Alvinias")
            ->setZip("014140-132")
            ->setCity("Cabrobró do Norte")
            ->setState("BA")
            ->setCountry("BR")
            ->setEstimatedDate("2026-09-30")
            ->setValue(12.50)
            ->setLat(-23.5)
            ->setLon(-46.6);
        $this->assertEquals("Mario Peruzzi", $address->getName());
        $this->assertEquals("Rua das Palmeiras, 454", $address->getAddress1());
        $this->assertEquals("Alvinias", $address->getAddress2());
        $this->assertEquals("014140-132", $address->getZip());
        $this->assertEquals("Cabrobró do Norte", $address->getCity());
        $this->assertEquals("BA", $address->getState());
        $this->assertEquals("BR", $address->getCountry());
        $this->assertEquals("2026-09-30", $address->getEstimatedDate());
        $this->assertEquals(12.5, $address->getValue());
        $this->assertEquals(-23.5, $address->getLat());
        $this->assertEquals(-46.6, $address->getLon());
    }
}