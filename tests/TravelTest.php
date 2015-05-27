<?php
require_once "konduto.php";
require_once "vendor/autoload.php";

class TravelTest extends \PHPUnit_Framework_TestCase {

    public function testBusTravelLeg() {
        $bus_info = new Konduto\Models\BusTravelLeg(array(
            "origin_city" => "São Paulo",
            "destination_city" => "São Francisco",
            "date" => "2018-12-25T18:00Z"
        ));
        $this->assertInstanceOf("Konduto\Models\TravelLeg", $bus_info);
        $this->assertInstanceOf("Konduto\Models\BusTravelLeg", $bus_info);
        $this->assertTrue($bus_info->is_valid());
        $bus_info->date("2018-12-25T18:00");
        $this->assertFalse($bus_info->is_valid());
        $this->assertArrayHasKey("date", $bus_info->get_errors());

        $this->assertEquals($bus_info->origin_city(), "São Paulo");
        $this->assertEquals($bus_info->destination_city(), "São Francisco");
        $this->assertEquals($bus_info->date(), "2018-12-25T18:00");
    }

    public function testFlightLeg() {
        $flight_info = new Konduto\Models\FlightLeg(array(
            "origin_city" => "São Paulo",
            "origin_airport" => "GRU",
            "destination_city" => "São Francisco",
            "date" => "2018-12-25T18:00Z",
            "number_of_connections" => 1,
            "class" => "economy",
            "fare_basis" => "Y"
        ));
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
        $passenger = new Konduto\Models\Passenger(array(
            "name" => "Júlia da Silva",
            "document" => "A1B2C3D4",
            "document_type" => "id",
            "dob" => "1970-01-01",
            "nationality" => "US",
            "frequent_traveler" => true,
            "special_needs" => false,
            "loyalty" => array(
              "program" => "advantage",
              "category" => "gold"
            )
        ));
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

        $this->assertTrue($passenger->is_valid());
        $passenger->dob("01-01-1970");
        $passenger->nationality("USA");
        $passenger->document_type("carteria de trabalho");
        $this->assertArrayHasKey("dob", $passenger->get_errors());
        $this->assertArrayHasKey("nationality", $passenger->get_errors());
        $this->assertArrayHasKey("document_type", $passenger->get_errors());
        $this->assertFalse($passenger->is_valid());

        $passenger->clean_errors();

        $passenger->dob("1970-01-01");
        $passenger->nationality("BR");
        $passenger->document_type("passport");

        $this->assertTrue($passenger->is_valid());
    }

    public function testFlight1() {
        $flight = new Konduto\Models\Flight(array(
            "departure" => array(
                "origin_city" => "São Paulo",
                "origin_airport" => "GRU",
                "destination_city" => "São Francisco",
                "destination_airport" => "SFO",
                "date" => "2018-12-25T18:00Z",
                "number_of_connections" => 1,
                "class" => "economy",
                "fare_basis" => "Y"
            ),
            "return" => array(
                "origin_city" => "São Paulo",
                "origin_airport" => "GRU",
                "destination_city" => "São Francisco",
                "destination_airport" => "SFO",
                "date" => "2018-12-30T18:00Z",
                "number_of_connections" => 1,
                "class" => "business"
            ),
            "passengers" => array(
                array(
                    "name" => "Júlia da Silva",
                    "document" => "A1B2C3D4",
                    "document_type" => "id",
                    "dob" => "1970-01-01",
                    "nationality" => "US",
                    "frequent_flyer" => true,
                    "special_needs" => false,
                    "loyalty" => array(
                        "program" => "aadvantage",
                        "category" => "gold"
                    )
                ),
                array(
                    "name" => "Carlos Siqueira",
                    "document" => "AB11223344",
                    "document_type" => "passport",
                    "dob" => "1970-12-01",
                    "nationality" => "US",
                    "frequent_flyer" => false,
                    "special_needs" => true,
                    "loyalty" => array(
                        "program" => "skymiles",
                        "category" => "silver"
                    )
                )
            )
        ));
        $this->assertInstanceOf("Konduto\Models\Flight", $flight);
        $this->assertInstanceOf("Konduto\Models\Travel", $flight);
        $this->assertInstanceOf("Konduto\Models\TravelLeg", $flight->departure());
        $this->assertInstanceOf("Konduto\Models\TravelLeg", $flight->return_leg());
        $passengers = $flight->passengers();
        $this->assertInstanceOf("Konduto\Models\Passenger", $passengers[0]);
    }

    public function testFlight2() {
        $flight = Konduto\Models\Travel::instantiate(array(
            "type" => "flight",
            "departure" => array(
                "origin_airport" => "GRU",
                "destination_airport" => "SFO",
                "date" => "2018-12-25T18:00Z"
            )
        ));
        $this->assertInstanceOf("Konduto\Models\Flight", $flight);
        $this->assertInstanceOf("Konduto\Models\FlightLeg", $flight->departure());
    }

    public function testBusTravel() {
        $bus_travel = Konduto\Models\Travel::instantiate(array(
            "type" => "bus",
            "departure" => array(
                "origin_city" => "Campinas",
                "destination_city" => "São José dos Campos",
                "date" => "2018-12-25T18:00Z"
            )
        ));
        $this->assertInstanceOf("Konduto\Models\BusTravel", $bus_travel);
        $this->assertInstanceOf("Konduto\Models\BusTravelLeg", $bus_travel->departure());
    }


    public function testBusTravelWithReturn() {
        $bus_travel = new Konduto\Models\BusTravel(array(
            "departure" => array(
                "origin_city" => "Campinas",
                "destination_city" => "São José dos Campos",
                "date" => "2018-12-25T18:00Z"
            ),
            "return_leg" => array(
                "origin_city" => "São José dos Campos",
                "destination_city" => "Campinas",
                "date" => "2018-12-30T12:10Z"
            )
        ));

        $this->assertEquals(array(
            "type" => "bus",
            "departure" => array(
                "origin_city" => "Campinas",
                "destination_city" => "São José dos Campos",
                "date" => "2018-12-25T18:00Z"
            ),
            "return" => array(
                "origin_city" => "São José dos Campos",
                "destination_city" => "Campinas",
                "date" => "2018-12-30T12:10Z"
            )
        ), $bus_travel->to_array());

        $bus_travel->return_leg()->date("2018-12-30T15:30Z");

        $this->assertEquals(array(
            "type" => "bus",
            "departure" => array(
                "origin_city" => "Campinas",
                "destination_city" => "São José dos Campos",
                "date" => "2018-12-25T18:00Z"
            ),
            "return" => array(
                "origin_city" => "São José dos Campos",
                "destination_city" => "Campinas",
                "date" => "2018-12-30T15:30Z"
            )
        ), $bus_travel->to_array());
    }

    public function testOrderTravel() {
        $order_arr = array(
            "id"          => "Pedido100001834",
            "visitor"     => "da39a3ee5e6b4b0d3255bfef95601890afd80709",
            "total_amount" => 4312.71,
            "currency"    => "BRL",
            "customer"    => array(
                "id"     => "Customer n03",
                "name"   => "Hiroyuki Endo",
                "email"  => "endo.hiroyuki@yahoo.jp"
            ),
            "payment" => array(
                array(
                    "type" => "credit",
                    "bin" => "490172",
                    "last4"=> "0012",
                    "expiration_date" => "072015",
                    "status" => "approved"
                )
            ),
            "travel" => array(
                "type" => "flight",
                "departure" => array(
                    "origin_airport" => "GRU",
                    "destination_airport" => "YYZ",
                    "date" => "2016-11-10T23:00Z"
                ),
                "return" => array(
                    "origin_airport" => "YYZ",
                    "destination_airport" => "GRU",
                    "date" => "2017-02-02T19:20Z"
                )
            )
        );
        $order = new Konduto\Models\Order($order_arr);
        $this->assertInstanceOf("Konduto\Models\Travel", $order->travel());
        $this->assertInstanceOf("Konduto\Models\Flight", $order->travel());

        $this->assertFalse($order->is_valid());
        $this->assertArrayHasKey("travel", $order->get_errors());
        $this->assertArrayHasKey("passengers", $order->travel()->get_errors());

        $a_passenger = array(
            "name" => "Júlia da Silva",
            "document" => "A1B2C3D4",
            "document_type" => "id",
            "nationality" => "BR"
        );
        $order->travel()->passengers(array($a_passenger));

        $order_arr["travel"]["passengers"] = array($a_passenger);
        $this->assertEquals($order_arr, $order->to_array());

        $this->assertTrue($order->is_valid());
    }
}
