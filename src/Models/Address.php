<?php namespace Konduto\Models;

class Address extends Geolocation {

    protected $_schema_key = 'address';

    protected $_properties = [
        "name" => null, 
        "address1" => null, 
        "address2" => null, 
        "city" => null,
        "state" => null,
        "zip" => null,
        "country" => null
    ];
}