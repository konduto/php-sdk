<?php namespace Konduto\Models;

class TravelLeg extends Model {

    protected $_schema_key = "travel_info";

    protected $_properties = array(
        "origin_city" => null,
        "origin_airport" => null,
        "destination_city" => null,
        "destination_airport" => null,
        "date" => null,
        "number_of_connections" => null,
        "class" => null,
        "fare_basis" => null
    );
}
