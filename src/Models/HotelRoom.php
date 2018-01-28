<?php namespace Konduto\Models;

use Konduto\Parsers\DateParser;
use Konduto\Parsers\HotelRoomGuestArrayParser;

class HotelRoom extends BaseModel {

    /**
     * Given an array, instantiates a payment among the possible
     * types of payments. The decision of what Model to use is made
     * by field 'type'
     * @param $array: array containing fields of the Payment
     * @return Payment CreditCard or Boleto object
     */
    public static function build(array $array) {
        return new HotelRoom($array);
    }

    /**
     * @inheritDoc
     */
    protected function initParsers() {
        return array(
            "check_in_date"  => new DateParser(),
            "check_out_date" => new DateParser(),
            "guests"         => new HotelRoomGuestArrayParser()
        );
    }

    /**
     * @inheritdoc
     */
    protected function fields() {
        return array(
            'number',
            'code',
            'type',
            'check_in_date',
            'check_out_date',
            'number_of_guests',
            'board_basis',
            'guests'
        );
    }

    public function getGuests()
    {
        return $this->get('guests');
    }
}
