<?php

namespace Konduto\Parsers;

use Konduto\Models\Bank;

class BankArrayParser extends DefaultParser
{

    public function parse($value)
    {
        if (!is_array($value))
            $value = array($value);
        foreach ($value as $i => $keyType) {
            if (is_array($keyType))
                $value[$i] = Bank::build($keyType);
        }
        return $value;
    }
}