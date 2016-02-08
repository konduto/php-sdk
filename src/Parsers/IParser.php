<?php namespace Konduto\Parsers;

interface IParser {
    public function parse($value);
    public function unparse($value);
}
