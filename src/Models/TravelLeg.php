<?php namespace Konduto\Models;

class TravelLeg extends Model {

    protected $_schema_key = "travel_info";

    protected $_properties = [
        "origin_city" => null,
        "origin_airport" => null,
        "destination_city" => null,
        "destination_airport" => null,
        "date" => null,
        "number_of_connections" => null,
        "class" => null,
        "fare_basis" => null
    ];
}

class BusTravelLeg extends TravelLeg {
    protected $_mandatory_fields = ["origin_city", "destination_city", "date"];
}

class FlightLeg extends TravelLeg {
    protected $_mandatory_fields = ["origin_airport", "destination_airport", "date"];
}
