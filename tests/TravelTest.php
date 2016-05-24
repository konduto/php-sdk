<?php namespace Konduto\Tests;

use Konduto\Models\Travel;

class TravelTest extends \PHPUnit_Framework_TestCase {

    function test_cc1() {
        $travelArr = array(
            "type" => "flight",
            "departure" => array(
                "origin_airport" => "GRU",
                "destination_airport" => "SFO",
                "date" => "2018-12-25T18:00Z",
                "number_of_connections" => 1,
                "class" => "economy",
                "fare_basis" => "Y"
            ),
            "return" => array(
                "origin_airport" => "SFO",
                "destination_airport" => "GRU",
                "date" => "2018-12-30T18:00Z",
                "number_of_connections" => 1,
                "class" => "business"
            ),
            "passengers" => array(
                array(
                    "name" => "Júlia da Silva",
                    "document" => "12345678909",
                    "document_type" => "id",
                    "dob" => "1970-01-01",
                    "nationality" => "BR",
                    "loyalty" => array(
                        "program" => "smiles",
                        "category" => "gold"
                    ),
                    "frequent_traveler" => true,
                    "special_needs" => false
                ),
                array(
                    "name" => "Carlos Siqueira",
                    "document" => "XYZ1234",
                    "document_type" => "passport",
                    "dob" => "1970-12-01",
                    "nationality" => "US",
                    "loyalty" => array(
                        "program" => "multiplus",
                        "category" => "silver"
                    ),
                    "frequent_traveler" => false,
                    "special_needs" => true
                )
            )
        );
        $travel = Travel::build($travelArr);
        $passengers = $travel->getPassengers();
        $this->assertInstanceOf('Konduto\Models\Flight', $travel);
        $this->assertInstanceOf('Konduto\Models\FlightLeg', $travel->getDeparture());
        $this->assertInstanceOf('Konduto\Models\FlightLeg', $travel->getReturn());
        $this->assertInstanceOf('Konduto\Models\Passenger', $passengers[0]);
        $this->assertInstanceOf('Konduto\Models\Passenger', $passengers[1]);
        $this->assertInstanceOf('Konduto\Models\Loyalty', $passengers[1]->get("loyalty"));
        $travelJsonArray = $travel->toJsonArray();
        $this->assertEquals($travelArr, $travelJsonArray);
    }
}