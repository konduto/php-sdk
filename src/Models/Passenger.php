<?php namespace Konduto\Models;

class Passenger extends Model {

    protected $_schema_key = "passenger";

    protected $_properties = array(
        "name" => null,
        "document" => null,
        "document_type" => null,
        "dob" => null,
        "nationality" => null,
        "loyalty" => null,
        "frequent_traveler" => null,
        "special_needs" => null
    );

    protected $_mandatory_fields = array("name", "document", "document_type");

    public function loyalty($value = null) {
        return $this->set_get_object("loyalty", $value, "Konduto\Models\Loyalty");
    }
}
