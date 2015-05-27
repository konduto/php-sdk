<?php namespace Konduto\Models;

abstract class Travel extends Model {

    const TYPE_BUS = "bus";
    const TYPE_FLIGHT = "flight";

    protected $_schema_key = "travel";

    protected $_properties = array(
        "type" => self::TYPE_FLIGHT,
        "departure" => null,
        "return_leg" => null,
        "passengers" => array()
    );

    protected $_mandatory_fields = array("type", "departure", "passengers");

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

    abstract public function departure($value = null);

    abstract public function return_leg($value = null);

    public function passengers($passenger_array = null) {
        return $this->set_get_array_object("passengers",
                 $passenger_array, "Konduto\Models\Passenger");
    }
}
