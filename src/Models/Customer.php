<?php namespace Konduto\Models;

class Customer extends Model {

    protected $_schema_key = "customer";

    protected $_properties = array(
        "id" => null,
        "name" => null,
        "tax_id" => null,
        "phone1" => null,
        "phone2" => null,
        "email" => null,
        "is_new" => null,
        "vip" => null
    );

    protected $_mandatory_fields = array("id", "name", "email");
}
