<?php namespace Konduto\Parsers;

use Konduto\Models\Travel;

class TravelParser extends DefaultParser {

    public function parse($value) {
        if (is_array($value))
            $value = Travel::build($value);
        return $value;
    }
}