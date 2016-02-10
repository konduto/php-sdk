<?php namespace Konduto\Parsers;

class ArrayModelParser extends ModelParser {

    public function parse($value) {
        if (!is_array($value))
            $value = array($value);
        foreach ($value as $i => $item)
            $value[$i] = parent::parse($item);
        return $value;
    }
}