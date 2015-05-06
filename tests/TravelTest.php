<?php
require_once "konduto.php";
require_once "vendor/autoload.php";

class TravelTest extends \PHPUnit_Framework_TestCase {

    public function testBusTravelLeg() {
        $bus_info = new Konduto\Models\BusTravelLeg([
            "origin_city" => "São Paulo",
            "destination_city" => "São Francisco",
            "date" => "2018-12-25T18:00Z"
        ]);
        $this->assertInstanceOf("Konduto\Models\TravelLeg", $bus_info);
        $this->assertInstanceOf("Konduto\Models\BusTravelLeg", $bus_info);
        $this->assertTrue($bus_info->is_valid());
        $bus_info->date("2018-12-25T18:00");
        $this->assertFalse($bus_info->is_valid());
        $this->assertArrayHasKey("date", $bus_info->get_errors());

        $this->assertEquals($bus_info->origin_city(), "São Paulo");
        $this->assertEquals($bus_info->destination_city(), "São Francisco");
        $this->assertEquals($bus_info->date(), "2018-12-25T18:00Z");
    }

    public function testFlightLeg() {
        $flight_info = new Konduto\Models\FlightLeg([
            "origin_city" => "São Paulo",
            "origin_airport" => "GRU",
            "destination_city" => "São Francisco",
            "date" => "2018-12-25T18:00Z",
            "number_of_connections" => 1,
            "class" => "economy",
            "fare_basis" => "Y"
        ]);
        $this->assertInstanceOf("Konduto\Models\TravelLeg", $flight_info);
        $this->assertInstanceOf("Konduto\Models\FlightLeg", $flight_info);

        $this->assertFalse($flight_info->is_valid());
        $this->assertArrayHasKey("destination_airport", $flight_info->get_errors());
        $flight_info->destination_airport("SanFrancisco");

        $this->assertFalse($flight_info->is_valid());
        $this->assertArrayHasKey("destination_airport", $flight_info->get_errors());
        $flight_info->destination_airport("SFO");
        $this->assertTrue($flight_info->is_valid());

        $flight_info->class("lala");
        $this->assertFalse($flight_info->is_valid());
        $this->assertArrayHasKey("class", $flight_info->get_errors());
        $flight_info->class("first");

        $this->assertTrue($flight_info->is_valid());

        $this->assertEquals($flight_info->origin_city(), "São Paulo");
        $this->assertEquals($flight_info->origin_airport(), "GRU");
        $this->assertEquals($flight_info->destination_city(), "São Francisco");
        $this->assertEquals($flight_info->destination_airport(), "SFO");
        $this->assertEquals($flight_info->number_of_connections(), 1);
        $this->assertEquals($flight_info->class(), "first");
        $this->assertEquals($flight_info->fare_basis(), "Y");
        $this->assertEquals($flight_info->date(), "2018-12-25T18:00Z");
    }

    public function testPassenger() {
        $passenger = new Konduto\Models\Passenger([
            "name" => "Júlia da Silva",
            "document" => "A1B2C3D4",
            "document_type" => "id",
            "dob" => "1970-01-01",
            "nationality" => "US",
            "frequent_traveler" => true,
            "special_needs" => false,
            "loyalty" => [
              "program" => "advantage",
              "category" => "gold"
            ]
        ]);
        $this->assertInstanceOf("Konduto\Models\Passenger", $passenger);
        $this->assertInstanceOf("Konduto\Models\Loyalty", $passenger->loyalty());
        $this->assertEquals("Júlia da Silva", $passenger->name());
        $this->assertEquals("A1B2C3D4", $passenger->document());
        $this->assertEquals("id", $passenger->document_type());
        $this->assertEquals("1970-01-01", $passenger->dob());
        $this->assertEquals("US", $passenger->nationality());
        $this->assertEquals(true, $passenger->frequent_traveler());
        $this->assertEquals(false, $passenger->special_needs());
        $this->assertEquals("advantage", $passenger->loyalty()->program());
        $this->assertEquals("gold", $passenger->loyalty()->category());
    }

    public function testFlight() {
        $flight = new Konduto\Models\Flight([
            "departure" => [
                "origin_city" => "São Paulo",
                "origin_airport" => "GRU",
                "destination_city" => "São Francisco",
                "destination_airport" => "SFO",
                "date" => "2018-12-25T18:00Z",
                "number_of_connections" => 1,
                "class" => "economy",
                "fare_basis" => "Y"
            ],
            "return" => [
                "origin_city" => "São Paulo",
                "origin_airport" => "GRU",
                "destination_city" => "São Francisco",
                "destination_airport" => "SFO",
                "date" => "2018-12-30T18:00Z",
                "number_of_connections" => 1,
                "class" => "business"
            ],
            "passengers" => [
                [
                    "name" => "Júlia da Silva",
                    "document" => "A1B2C3D4",
                    "document_type" => "id",
                    "dob" => "1970-01-01",
                    "nationality" => "US",
                    "frequent_flyer" => true,
                    "special_needs" => false,
                    "loyalty" => [
                        "program" => "aadvantage",
                        "category" => "gold"
                    ]
                ],
                [
                    "name" => "Carlos Siqueira",
                    "document" => "AB11223344",
                    "document_type" => "passport",
                    "dob" => "1970-12-01",
                    "nationality" => "US",
                    "frequent_flyer" => false,
                    "special_needs" => true,
                    "loyalty" => [
                        "program" => "skymiles",
                        "category" => "silver"
                    ]
                ]
            ]
        ]);
        $this->assertInstanceOf("Konduto\Models\Flight", $flight);
        $this->assertInstanceOf("Konduto\Models\Travel", $flight);
        $this->assertInstanceOf("Konduto\Models\TravelLeg", $flight->departure());
        $this->assertInstanceOf("Konduto\Models\TravelLeg", $flight->return_leg());
        $this->assertInstanceOf("Konduto\Models\Passenger", $flight->passengers()[0]);
    }
}
