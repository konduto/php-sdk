<?php namespace Konduto\Models;

class Flight extends Travel {

    public function __construct() {
        parent::__construct(func_num_args() == 1 ? func_get_arg(0) : func_get_args());
        // Set 'flight' to type
        $this->set(array("type" => Travel::TYPE_FLIGHT));
    }

    public function departure($value = null) {
        return $this->set_get_object("departure", $value, "Konduto\Models\FlightLeg");
    }

    public function return_leg($value = null) {
        return $this->set_get_object("return", $value, "Konduto\Models\FlightLeg");
    }
}
