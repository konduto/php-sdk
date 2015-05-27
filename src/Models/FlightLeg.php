<?php namespace Konduto\Models;

class FlightLeg extends TravelLeg {
    protected $_mandatory_fields = array("origin_airport", "destination_airport", "date");
}
