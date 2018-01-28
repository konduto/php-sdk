<?php namespace Konduto\Models;

use Konduto\Parsers\ModelParser;
use Konduto\Parsers\DateParser;
use Konduto\Parsers\HotelRoomArrayParser;

class Hotel extends BaseModel {

    /**
     * @inheritdoc
     */
    protected function fields() {
        return array(
            'name', 'address1', 'address2',
            'city', 'state', 'zip', 'country',
            'category', 'rooms'
        );
    }

    /**
     * @inheritdoc
     */
    protected function initParsers() {
        return array(
            "rooms" => new HotelRoomArrayParser(),
        );
    }



    public function getRoom()
    {
        return $this->get('rooms');
    }

    public function setRoom($array)
    {
        $this->set('rooms', $array);
    }
}
