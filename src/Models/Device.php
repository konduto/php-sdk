<?php namespace Konduto\Models;

class Device extends Model {

    protected $_properties = array(
        "user_id" => null,
        "fingerprint" => null,
        "platform" => null,
        "browser" => null,
        "language" => null,
        "timezone" => null,
        "cookie" => null,
        "javascript" => null,
        "flash" => null
    );
}
