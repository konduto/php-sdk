<?php namespace Konduto\Models;

use Konduto\Parsers\DateParser;

class HotelRoomGuest extends BaseModel {

    /**
     * Given an array, instantiates a payment among the possible
     * types of payments. The decision of what Model to use is made
     * by field 'type'
     * @param $array: array containing fields of the Payment
     * @return Payment CreditCard or Boleto object
     */
    public static function build(array $array) {
        return new HotelRoomGuest($array);
    }

    /**
     * @inheritDoc
     */
    protected function initParsers() {
        return array(
            "dob" => new DateParser(),
        );
    }

    /**
     * @inheritdoc
     */
    protected function fields() {
        return array(
            'name',
            'document',
            'document_type',
            'dob',
            'nationality'
        );
    }
}
