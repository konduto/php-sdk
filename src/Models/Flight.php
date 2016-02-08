<?php namespace Konduto\Models;

use Konduto\Parsers\ModelParser;

class Flight extends Travel {

    public function __construct($args) {
        parent::__construct($args);
        $this->setType(self::TYPE_FLIGHT);
    }

    /**
     * @inheritdoc
     */
    protected function parsers() {
        return array(
            "departure" => new ModelParser('Konduto\Models\FlightLeg'),
            "return" => new ModelParser('Konduto\Models\FlightLeg'),
        );
    }
}
