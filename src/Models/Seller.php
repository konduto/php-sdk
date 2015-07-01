<?php namespace Konduto\Models;

class Seller extends Model {

    protected $_schema_key = "seller";

    protected $_properties = array(
        "id" => null,
        "name" => null,
        "created_at" => null
    );

    protected $_mandatory_fields = array("id");
}
