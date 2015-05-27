<?php namespace Konduto\Models;

class BusTravelLeg extends TravelLeg {
    protected $_mandatory_fields = array("origin_city", "destination_city", "date");
}
