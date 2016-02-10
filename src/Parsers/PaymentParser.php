<?php namespace Konduto\Parsers;

use Konduto\Models\Payment;

class PaymentArrayParser extends DefaultParser {

    public function parse($value) {
        if (!is_array($value))
            $value = array($value);
        foreach ($value as $i => $item) {
            if (is_array($item))
                $value[$i] = Payment::build($item);
        }
        return $value;
    }
}