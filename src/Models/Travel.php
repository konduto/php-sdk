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

    /**
     * Given an array, instantiates a payment among the possible
     * types of travel. The decision of what Model to use is made
     * with field 'type'
     * @param $array_of_args: array containing fields of the Travel
     * @return a BusTravel or Flight object
     */
    public static function instantiate($array_of_args) {
        if (!is_array($array_of_args) || !array_key_exists("type", $array_of_args)) {
            return null;
        }
        switch ($array_of_args["type"]) {
            case Travel::TYPE_BUS: return new BusTravel($array_of_args);
            case Travel::TYPE_FLIGHT: return new Flight($array_of_args);
            default: return null;
        }
    }

    public function departure($value = null) {
        return $this->set_get_object("departure", $value, "Konduto\Models\TravelLeg");
    }

    public function return_leg($value = null) {
        return $this->set_get_object("return_leg", $value, "Konduto\Models\TravelLeg");
    }

    public function passengers($passenger_array = null) {
        return $this->set_get_array_object("passengers",
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
        return $this->set_get_object("departure", $value, "Konduto\Models\BusTravelLeg");
    }

    public function return_leg($value = null) {
        return $this->set_get_object("return_leg", $value, "Konduto\Models\BusTravelLeg");
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
