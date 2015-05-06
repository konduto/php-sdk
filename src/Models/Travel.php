<?php namespace Konduto\Models;

class Travel extends Model {

    const TYPE_BUS = "bus";
    const TYPE_FLIGHT = "flight";

    protected $_schema_key = "travel";

    protected $_properties = [
        "type" => self::TYPE_FLIGHT,
        "departure" => null,
        "return_leg" => null,
        "passengers" => []
    ];

    protected $_mandatory_fields = ["type", "departure", "passengers"];

    public function departure($value = null) {
        return $this->set_get_object("departure",
            $value, "Konduto\Models\TravelLeg");
    }

    public function return_leg($value = null) {
        return $this->set_get_object("return_leg",
            $value, "Konduto\Models\TravelLeg");
    }

    public function passengers($passenger_array = null) {
        return $this->set_get_array_object("passenger",
                 $passenger_array, "Konduto\Models\Passenger");
    }
}

class BusTravel extends Travel {

    public function __construct() {
        parent::__construct(func_num_args() == 1 ? func_get_arg(0) : func_get_args());
        // Set 'bus' to type
        $this->set("type", Travel::TYPE_BUS);
    }

    public function departure($value = null) {
        return $this->set_get_object("departure",
            $value, "Konduto\Models\BusTravelLeg");
    }

    public function return_leg($value = null) {
        return $this->set_get_object("return_leg",
            $value, "Konduto\Models\BusTravelLeg");
    }
}

class Flight extends Travel {

    public function __construct() {
        parent::__construct(func_num_args() == 1 ? func_get_arg(0) : func_get_args());
        // Set 'flight' to type
        $this->set("type", Travel::TYPE_FLIGHT);
    }

    public function departure($value = null) {
        return $this->set_get_object("departure",
            $value, "Konduto\Models\FlightLeg");
    }

    public function return_leg($value = null) {
        return $this->set_get_object("return_leg",
            $value, "Konduto\Models\FlightLeg");
    }
}
