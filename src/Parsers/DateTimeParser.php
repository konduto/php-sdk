<?php namespace Konduto\Parsers;

class DateTimeParser implements IParser {

    protected $outputFormat;

    public function __construct($format) {
        $this->outputFormat = $format;
    }

    public function parse($value) {
        return is_string($value) ?
            \DateTime::createFromFormat($this->outputFormat, $value, new \DateTimeZone('UTC')) : $value;
    }

    public function unparse($value) {
        if (is_a($value, 'DateTime')) {
            $value->setTimezone(new \DateTimeZone('UTC'));
            $value = date_format($value, $this->outputFormat);
        }
        return $value;
    }
}
