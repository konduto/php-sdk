<?php namespace Konduto\Parsers;

class ModelParser extends DefaultParser {

    protected $class;

    public function __construct($class) {
        if (!class_exists($class))
            throw new \InvalidArgumentException("'$class' is not a class");
        $this->class = $class;
    }

    public function parse($value) {
        if (is_array($value)) {
            $class = $this->class;
            return new $class($value);
        }
        return parent::parse($value);
    }
}