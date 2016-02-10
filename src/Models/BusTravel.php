<?php namespace Konduto\Models;

use Konduto\Parsers\ModelParser;

class BusTravel extends Travel {

    public function __construct($args) {
        parent::__construct($args);
        $this->setType(self::TYPE_BUS);
    }

    /**
     * @inheritdoc
     */
    protected function initParsers() {
        return array_replace(parent::initParsers(), array(
            "departure" => new ModelParser('Konduto\Models\BusTravelLeg'),
            "return" => new ModelParser('Konduto\Models\BusTravelLeg'),
        ));
    }
}
