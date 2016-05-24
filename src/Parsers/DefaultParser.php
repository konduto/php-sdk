<?php namespace Konduto\Parsers;

class DefaultParser implements IParser {

    public function parse($value) {
        return $value;
    }

    public function unparse($value) {
        if (is_a($value, 'Konduto\Models\BaseModel')) {
            $value = $value->toJsonArray();
        }
        else if (is_array($value)) {
            foreach ($value as $key => $arrVal)
                $value[$key] = $this->unparse($arrVal);
        }
        return $value;
    }
}