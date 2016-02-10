<?php namespace Konduto\Parsers;

class NullDateTimeUnparser extends DateTimeParser {

    public function unparse($value) {
        return null;
    }
}
