<?php namespace Konduto\Parsers;

use Konduto\Models\HotelRoomGuest;

class HotelRoomGuestArrayParser extends DefaultParser {

    public function parse($value) {
        if (!is_array($value))
            $value = array($value);
        foreach ($value as $i => $item) {
            if (is_array($item))
                $value[$i] = HotelRoomGuest::build($item);
        }
        return $value;
    }
}
