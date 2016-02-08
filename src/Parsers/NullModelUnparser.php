<?php namespace Konduto\Parsers;

class NullModelUnparser extends ModelParser {

    public function unparse($value) {
        return null;
    }
}
