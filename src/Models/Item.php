<?php namespace Konduto\Models;

class Item extends Model {

    protected $_schema_key = "item";

    protected $_properties = array(
        "sku" => null,
        "product_code" => null,
        "category" => null,
        "name" => null,
        "description" => null,
        "unit_cost" => null,
        "quantity" => null,
        "discount" => null
    );

}
