<?php namespace Konduto\Models;

class Boleto extends Payment {

    protected $_schema_key = "boleto";

    protected $_properties = [
        "type" => Payment::TYPE_BOLETO,
        "expiration_date" => null,
        "status" => null
    ];

    protected $_mandatory_fields = ["type"];
    
    public function status() {
        return null;
    }
}