<?php namespace Konduto\Parsers;

class NullUnparser extends DefaultParser {

    public function unparse($value) {
        return null;
    }
}
