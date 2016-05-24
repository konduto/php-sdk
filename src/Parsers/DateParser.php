<?php namespace Konduto\Parsers;

class DateParser extends DateTimeParser {

    public function __construct() {
        parent::__construct('Y-m-d');
    }
}