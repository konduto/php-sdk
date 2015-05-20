<?php namespace Konduto\Models;

class CreditCard extends Payment {

    protected $_schema_key = "credit_card";

    protected $_properties = array(
        "type" => Payment::TYPE_CARD,
        "status" => null,
        "sha1" => null,
        "bin" => null,
        "last4" => null,
        "expiration_date" => null
    );

    protected $_mandatory_fields = array("type", "status");
}
