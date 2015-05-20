<?php namespace Konduto\Models;

class Geolocation extends Model {

    protected $_schema_key = 'address';

    protected $_properties = array(
        "city" => null,
        "state" => null,
        "country" => null
    );
}
